<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/update-stock', [ProductController::class, 'updateStock'])
        ->name('products.update-stock');
    Route::get('products/{product}/stock-history', [ProductController::class, 'stockHistory'])
        ->name('products.stock-history');

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Sales Management
    Route::resource('sales', SaleController::class);
    Route::get('sales/{sale}/invoice', [SaleController::class, 'invoice'])
        ->name('sales.invoice');
    Route::post('sales/{sale}/void', [SaleController::class, 'void'])
        ->name('sales.void');
    Route::get('sales/{sale}/pdf', [SaleController::class, 'downloadPdf'])->name('sales.download-pdf');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Only Routes// User Management
        Route::resource('users', UserController::class);

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/products', [ReportController::class, 'products'])->name('products');
            Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
            Route::get('/users', [ReportController::class, 'users'])->name('users');
            Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
            Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
        });
});

require __DIR__.'/auth.php';
