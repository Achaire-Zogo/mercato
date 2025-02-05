<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index(): View
    {
        // Récupérer les ventes avec leurs relations
        $sales = Sale::with(['items.product', 'user'])->latest()->get();

        // Statistiques du jour
        $today = now()->startOfDay();
        $todaySales = Sale::whereDate('created_at', $today)->count();
        $todayRevenue = Sale::whereDate('created_at', $today)->sum('total_amount');

        // Statistiques du mois
        $startOfMonth = now()->startOfMonth();
        $monthSales = Sale::whereMonth('created_at', now()->month)->count();
        $monthRevenue = Sale::whereMonth('created_at', now()->month)->sum('total_amount');

        return view('sales.index', compact(
            'sales',
            'todaySales',
            'todayRevenue',
            'monthSales',
            'monthRevenue'
        ));
    }

    public function create(): View
    {
        $products = Product::select('id', 'name', 'price', 'stock_quantity')
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:cash,card',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $total_amount = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
                $total_amount += $product->price * $item['quantity'];
            }

            if ($validated['amount_paid'] < $total_amount) {
                throw new \Exception("Insufficient payment amount");
            }

            $sale = Sale::create([
                'user_id' => Auth::user()->id,
                'total_amount' => $total_amount,
                'amount_paid' => $validated['amount_paid'],
                'change_amount' => $validated['amount_paid'] - $total_amount,
                'payment_method' => $validated['payment_method'],
                'invoice_number' => 'INV-' . Str::random(10),
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                $product->decrement('stock_quantity', $item['quantity']);

                if ($product->stock_quantity <= $product->alert_threshold) {
                    // TODO: Implement low stock notification
                }
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale completed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());

        }
    }

    private function calculateTotals(Sale $sale): void
    {
        $subtotal = 0;

        // Calcul du total pour chaque ligne
        foreach ($sale->items as $item) {
            $item->line_total = $item->quantity * $item->unit_price;
            $subtotal += $item->line_total;
        }

        // Calcul de la TVA (20% du sous-total)
        $tax = $subtotal * 0.20;

        // Total avec TVA
        $total = $subtotal + $tax;

        // Ajout des calculs à l'objet sale
        $sale->subtotal = $subtotal;
        $sale->tax = $tax;
        $sale->total_amount = $total;
    }

    public function show(Sale $sale): View
    {
        $this->calculateTotals($sale);
        return view('sales.show', compact('sale'));
    }

    public function downloadPdf(Sale $sale)
    {
        $this->calculateTotals($sale);
        $pdf = PDF::loadView('sales.pdf', compact('sale'));
        return $pdf->download('facture-' . $sale->invoice_number . '.pdf');
    }

    public function generateInvoice(Sale $sale): JsonResponse
    {
        // TODO: Implement invoice generation using a PDF library
        return response()->json([
            'message' => 'Invoice generation not implemented yet'
        ]);
    }

    public function dailyReport(): JsonResponse
    {
        $report = Sale::whereDate('created_at', today())
            ->with(['items.product'])
            ->get()
            ->groupBy(function($sale) {
                return $sale->created_at->format('H:00');
            })
            ->map(function($sales) {
                return [
                    'count' => $sales->count(),
                    'total' => $sales->sum('total_amount'),
                ];
            });

        return response()->json($report);
    }

    public function monthlyReport(): JsonResponse
    {
        $report = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with(['items.product'])
            ->get()
            ->groupBy(function($sale) {
                return $sale->created_at->format('Y-m-d');
            })
            ->map(function($sales) {
                return [
                    'count' => $sales->count(),
                    'total' => $sales->sum('total_amount'),
                ];
            });

        return response()->json($report);
    }

    public function edit(Sale $sale)
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer'
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour le mode de paiement
            $sale->payment_method = $request->payment_method;
            $sale->save();

            // Supprimer les anciens items
            foreach ($sale->items as $item) {
                // Remettre en stock les quantités des anciens items
                $item->product->increment('stock_quantity', $item->quantity);
                $item->delete();
            }

            $total = 0;
            // Créer les nouveaux items
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                // Vérifier le stock
                if ($product->stock_quantity < $itemData['quantity']) {
                    throw new \Exception("Stock insuffisant pour {$product->name}");
                }

                // Créer le nouvel item
                $lineTotal = $itemData['quantity'] * $product->price;
                $saleItem = $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $lineTotal
                ]);

                // Calculer le total de la ligne
                $total += $lineTotal;

                // Mettre à jour le stock
                $product->decrement('stock_quantity', $itemData['quantity']);
            }

            // Mettre à jour les totaux de la vente
            $tax = $total * 0.20; // 20% TVA
            $sale->total_amount = $total + $tax;
            $sale->save();

            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', 'Vente mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
