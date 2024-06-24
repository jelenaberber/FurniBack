<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CheckJwtToken;

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index');
    Route::get('products/{id}', 'show');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
});

Route::controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh-token', 'refresh');
    Route::get('users/{id}', 'show');
    Route::put('users/{id}', 'update');
});

Route::post('/messages', [MessageController::class, 'store']);

Route::post('/order', [OrderController::class, 'store'])->middleware(CheckJwtToken::class);

Route::controller(CartController::class)->group(function () {
    Route::get('cart/addProduct/{id}', 'addProductToCart')->middleware(CheckJwtToken::class);
    Route::get('cart', 'index')->middleware(CheckJwtToken::class);
    Route::delete('cart/{id}', 'destroy')->middleware(CheckJwtToken::class);
    Route::delete('cart/product/{id}', 'deleteProduct')->middleware(CheckJwtToken::class);
});

Route::middleware([IsAdmin::class])->group(function () {
    Route::get('/messages', [MessageController::class, 'index']);
    Route::delete('/messages/{id}', [MessageController::class, 'destroy']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/{id}', [UserController::class, 'changeRole']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/products', [UserController::class, 'store']);
    Route::put('/products/{id}', [UserController::class, 'update']);
    Route::delete('/products/{id}', [UserController::class, 'destroy']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});
