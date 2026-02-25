<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])
        ->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('customers', [CustomerController::class, 'index'])
        ->name('customers.index');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])
        ->name('customers.show');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])
        ->name('customers.update');
    Route::patch('customers/{customer}/block', [CustomerController::class, 'toggleBlock'])
        ->name('customers.block');
    Route::get('orders', [OrderController::class, 'index'])
        ->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.status');
});

require __DIR__.'/auth.php';
