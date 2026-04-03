<?php

use App\Http\Controllers\{
    DashboardController,
    ProductController,
    CostingController,
    MaterialController,
    RateConfigurationController,
    ProfileController,
    UserController,
    StoneTypeController,
    OxoFactorController,
    CategoryController
};
use Illuminate\Support\Facades\Route;

// Public route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes (login, register, etc.)
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Costing
    Route::prefix('costing')->name('costing.')->group(function () {
        Route::get('/', [CostingController::class, 'index'])->name('index');
        Route::get('/calculator', [CostingController::class, 'calculator'])->name('calculator');
        Route::get('/calculate/{product}', [CostingController::class, 'calculate'])->name('calculate');
        Route::post('/store', [CostingController::class, 'store'])->name('store');
        Route::post('/quick-calculate', [CostingController::class, 'quickCalculate'])->name('quick-calculate');
        Route::get('/history', [CostingController::class, 'history'])->name('history');
        Route::get('/bulk', [CostingController::class, 'bulk'])->name('bulk');
        Route::post('/bulk/calculate', [CostingController::class, 'bulkCalculate'])->name('bulk.calculate');
    });

    // Materials
    Route::resource('materials', MaterialController::class);

    // Rate Configurations
    Route::resource('rates', RateConfigurationController::class);
    Route::post('rates/bulk-update', [RateConfigurationController::class, 'bulkUpdate'])->name('rates.bulk-update');

    // Users
    Route::resource('users', UserController::class);

    // Masters
    Route::resource('categories', CategoryController::class);
    Route::resource('stone-types', StoneTypeController::class);
    Route::resource('oxo-factors', OxoFactorController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
