<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
// Products
Route::resource('products', ProductController::class);

// Categories
Route::resource('categories', CategoryController::class);

// Customers
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
    Route::patch('/{customer}/block', [CustomerController::class, 'toggleBlock'])->name('block');
    Route::post('/bulk', [CustomerController::class, 'bulk'])->name('bulk');
});

// Orders
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('status');
});

// Files
Route::resource('files', FileController::class);
Route::get('files/download/{file}', [FileController::class, 'download'])->name('files.download');
Route::get('files/preview/{file}', [FileController::class, 'preview'])->name('files.preview');

require __DIR__ . '/auth.php';