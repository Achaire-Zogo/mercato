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

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['items.product', 'user'])->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request): JsonResponse
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

            return response()->json([
                'message' => 'Sale completed successfully',
                'sale' => $sale->load('items.product'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error processing sale',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Sale $sale): View
    {
        $sale->load(['items.product', 'user']);
        return view('sales.show', compact('sale'));
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
}
