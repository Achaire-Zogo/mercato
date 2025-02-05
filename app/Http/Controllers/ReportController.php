<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $sales = $query->latest()->paginate(15);
        $totalSales = $query->sum('total_amount');
        $averageSale = $sales->count() > 0 ? $totalSales / $sales->count() : 0;

        return view('reports.sales', compact('sales', 'totalSales', 'averageSale'));
    }

    public function products(Request $request)
    {
        $query = Product::withCount(['saleItems as total_sold']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15);

        return view('reports.products', compact('products'));
    }

    public function inventory()
    {
        $lowStock = Product::whereColumn('stock_quantity', '<=', 'alert_threshold')->get();
        $outOfStock = Product::where('stock_quantity', 0)->get();
        $products = Product::all();

        return view('reports.inventory', compact('lowStock', 'outOfStock', 'products'));
    }

    public function users()
    {
        $users = User::withCount(['sales'])->get();
        $topSellers = User::withCount(['sales'])
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        return view('reports.users', compact('users', 'topSellers'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sales,products,inventory,users',
            'start_date' => 'required_if:type,sales|date',
            'end_date' => 'required_if:type,sales|date|after_or_equal:start_date',
        ]);

        switch ($request->type) {
            case 'sales':
                return $this->generateSalesReport($request);
            case 'products':
                return $this->generateProductsReport();
            case 'inventory':
                return $this->generateInventoryReport();
            case 'users':
                return $this->generateUsersReport();
        }
    }

    public function export($type)
    {
        switch ($type) {
            case 'sales':
                return $this->exportSales();
            case 'products':
                return $this->exportProducts();
            case 'inventory':
                return $this->exportInventory();
            case 'users':
                return $this->exportUsers();
            default:
                abort(404);
        }
    }

    private function generateSalesReport(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $sales = $query->get();
        $totalSales = $sales->sum('total_amount');
        $averageSale = $sales->count() > 0 ? $totalSales / $sales->count() : 0;

        return [
            'total_sales' => $totalSales,
            'average_sale' => $averageSale,
            'total_transactions' => $sales->count(),
            'sales' => $sales
        ];
    }

    private function generateProductsReport()
    {
        return Product::withCount(['saleItems as total_sold'])
            ->orderBy('total_sold', 'desc')
            ->get();
    }

    private function generateInventoryReport()
    {
        return Product::select([
            'name',
            'matricule',
            'stock_quantity',
            'alert_threshold',
            'price'
        ])->get();
    }

    private function generateUsersReport()
    {
        return User::withCount(['sales'])
            ->orderBy('sales_count', 'desc')
            ->get();
    }

    private function exportSales()
    {
        $sales = Sale::with(['user', 'items.product'])->get();
        $csv = $this->arrayToCsv($sales->map(function ($sale) {
            return [
                'Invoice' => $sale->invoice_number,
                'Date' => $sale->created_at->format('Y-m-d H:i:s'),
                'Cashier' => $sale->user->name,
                'Total' => $sale->total_amount,
                'Items' => $sale->items->count(),
            ];
        })->toArray());

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales.csv"',
        ]);
    }

    private function exportProducts()
    {
        $products = Product::withCount(['saleItems as total_sold'])->get();
        $csv = $this->arrayToCsv($products->map(function ($product) {
            return [
                'Name' => $product->name,
                'SKU' => $product->matricule,
                'Stock' => $product->stock_quantity,
                'Price' => $product->price,
                'Total Sold' => $product->total_sold,
            ];
        })->toArray());

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }

    private function exportInventory()
    {
        $products = Product::all();
        $csv = $this->arrayToCsv($products->map(function ($product) {
            return [
                'Name' => $product->name,
                'SKU' => $product->matricule,
                'Stock' => $product->stock_quantity,
                'Alert Threshold' => $product->alert_threshold,
                'Price' => $product->price,
            ];
        })->toArray());

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory.csv"',
        ]);
    }

    private function exportUsers()
    {
        $users = User::withCount(['sales'])->get();
        $csv = $this->arrayToCsv($users->map(function ($user) {
            return [
                'Name' => $user->name,
                'Email' => $user->email,
                'Role' => $user->role,
                'Total Sales' => $user->sales_count,
            ];
        })->toArray());

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ]);
    }

    private function arrayToCsv($array)
    {
        if (count($array) == 0) {
            return '';
        }
        
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }
}
