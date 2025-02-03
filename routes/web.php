<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return redirect()->route('auth.showLoginForm');
});

// Auth
Route::get('auth/showLoginForm', action: [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('auth/showRegisterForm', action: [AuthController::class, 'showRegisterForm'])->name('auth.showRegisterForm');
Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::resource('users', controller: UserController::class)->middleware('auth');

// Products
Route::prefix('item')->name('products.')->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('index');
    Route::get('products/create', [ProductController::class, 'create'])->name('create');
    Route::post('products', [ProductController::class, 'store'])->name('store');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('products/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('destroy');
});


