<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Store Management Routes
    Route::middleware(['permission:manage stores'])->group(function () {
        Route::resource('stores', StoreController::class);
    });

    // Product Management Routes
    Route::middleware(['permission:manage inventory'])->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Transaction Routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])
            ->middleware(['permission:view transactions'])
            ->name('transactions.index');
            
        Route::get('/create', [TransactionController::class, 'create'])
            ->middleware(['permission:create transactions'])
            ->name('transactions.create');
    
        Route::post('/', [TransactionController::class, 'store'])
            ->middleware(['permission:create transactions'])
            ->name('transactions.store');
    
        Route::get('/{transaction}', [TransactionController::class, 'show'])
            ->middleware(['permission:view transactions'])
            ->name('transactions.show');
    
        Route::get('/{transaction}/print', [TransactionController::class, 'print'])
            ->middleware(['permission:view transactions'])
            ->name('transactions.print');
    });    

    /// Inventory Routes
    Route::prefix('inventory')->middleware(['permission:view inventory'])->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
        Route::middleware(['permission:manage inventory'])->group(function () {
            Route::get('/{inventory}', [InventoryController::class, 'show'])->name('inventory.show');
            Route::put('/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
        });
    });

    // Report Routes
    Route::prefix('reports')->middleware(['permission:view reports'])->group(function () {
        Route::get('/sales', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
        Route::get('/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');

        // Report Exports
        Route::get('/sales/export', [ReportController::class, 'exportSales'])->name('reports.sales.export');
        Route::get('/inventory/export', [ReportController::class, 'exportInventory'])->name('reports.inventory.export');
    });

    // User Management Routes (Owner Only)
    Route::middleware(['role:owner'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/roles', [UserController::class, 'updateRoles'])
            ->name('users.roles.update');
    });

    // API Routes for AJAX requests
    Route::prefix('api')->group(function () {
        Route::get('/products/search', [ProductController::class, 'search'])
            ->name('api.products.search');
            
        Route::get('/inventory/check', [InventoryController::class, 'checkStock'])
            ->name('api.inventory.check');
    });
});

require __DIR__.'/auth.php';