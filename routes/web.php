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
    Route::prefix('products')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/', [ProductController::class, 'index'])
            ->middleware(['permission:view products'])
            ->name('products.index');
    
        Route::middleware(['permission:manage products'])->group(function () {
            Route::get('/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/', [ProductController::class, 'store'])->name('products.store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });
    
        Route::get('/search', [ProductController::class, 'search'])
            ->middleware(['permission:view products'])
            ->name('api.products.search');
    
        Route::get('/{product}', [ProductController::class, 'show'])
            ->middleware(['permission:view products'])
            ->name('products.show');
        Route::get('/{product}/stores', [ProductController::class, 'showStores'])
            ->middleware(['permission:view products'])
            ->name('products.stores');
    });    

    // Stores Routes
    Route::prefix('stores')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/', [StoreController::class, 'index'])
            ->middleware(['permission:view stores'])
            ->name('stores.index');
    
        Route::middleware(['permission:manage stores'])->group(function () {
            Route::get('/create', [StoreController::class, 'create'])->name('stores.create');
            Route::post('/', [StoreController::class, 'store'])->name('stores.store');
            Route::get('/{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
            Route::put('/{store}', [StoreController::class, 'update'])->name('stores.update');
            Route::delete('/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
        });

        Route::get('/{store}/products/add', [StoreController::class, 'addProducts'])->name('stores.products.add');

        Route::post('/{store}/products', [StoreController::class, 'storeProducts'])->name('stores.products.store');
    
        Route::get('/{store}/products', [StoreController::class, 'products'])
            ->middleware(['permission:view stores'])
            ->name('stores.products');
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
    
            Route::get('/{inventory}/adjust-stock', [InventoryController::class, 'adjustStockForm'])->name('inventory.adjust-stock.form');
            Route::put('/{inventory}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('inventory.adjust-stock');
    
            Route::get('/{inventory}/transfer-stock', [InventoryController::class, 'transferStockForm'])->name('inventory.transfer-stock.form');
            Route::post('/transfer', [InventoryController::class, 'transfer'])->name('inventory.transfer');
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