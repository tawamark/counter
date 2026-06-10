<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivergenceController;
use App\Http\Controllers\InventoryCountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('/divergences', [DivergenceController::class, 'index'])->name('divergences.index');
    Route::post('/inventory-counts/{inventoryCount}/approve', [InventoryCountController::class, 'approve'])->name('inventory-counts.approve');
    Route::post('/inventory-counts/{inventoryCount}/finish', [InventoryCountController::class, 'finish'])->name('inventory-counts.finish');
    Route::post('/inventory-counts/{inventoryCount}/items', [InventoryCountController::class, 'updateItems'])->name('inventory-counts.items.update');
    Route::resource('inventory-counts', InventoryCountController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
