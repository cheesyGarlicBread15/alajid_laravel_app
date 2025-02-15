<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('auth.showLoginForm');
});

// Products
// TODO: rename index to productsList
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('products/{id}/edit', [ProductController::class,'edit'])->name('products.edit');
Route::put('products/{id}', [ProductController::class,'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class,'destroy'])->name('products.destroy');

// Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.showRegisterForm');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('users', [UserController::class])->middleware('auth');

// Users
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('users/{id}/edit', [UserController::class,'edit'])->name('users.edit');
Route::put('users/{id}', [UserController::class,'update'])->name('users.update');
Route::delete('users/{id}', [UserController::class,'destroy'])->name('users.destroy');
Route::get('logs', [UserController::class, 'logs'])->name('users.logs');