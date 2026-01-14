<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnitController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventory routes
    Route::get('/inventory/add-stock', [InventoryItemController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('/inventory/add-stock', [InventoryItemController::class, 'storeAddStock'])->name('inventory.store-add-stock');
    Route::get('/inventory/deduct-stock', [InventoryItemController::class, 'deductStock'])->name('inventory.deduct-stock');
    Route::post('/inventory/deduct-stock', [InventoryItemController::class, 'storeDeductStock'])->name('inventory.store-deduct-stock');
    Route::resource('inventory', InventoryItemController::class);

    // API routes
    Route::get('/api/units', [UnitController::class, 'index'])->name('api.units');
});

require __DIR__.'/auth.php';
