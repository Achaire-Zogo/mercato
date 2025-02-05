<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's sales total
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');

        // Get total products count
        $totalProducts = Product::count();

        // Get low stock products count
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'alert_threshold')->count();

        // Get total users count
        $totalUsers = User::count();

        // Get low stock items for display
        $lowStockItems = Product::whereColumn('stock_quantity', '<=', 'alert_threshold')
            ->latest()
            ->take(5)
            ->get();

        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Get recent sales
        $recentSales = Sale::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'todaySales',
            'totalProducts',
            'lowStockProducts',
            'totalUsers',
            'lowStockItems',
            'recentUsers',
            'recentSales'
        ));
    }
}
