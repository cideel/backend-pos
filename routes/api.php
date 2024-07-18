<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;

// Rute untuk registrasi dan login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rute untuk menu item
Route::get('/menu-items', [MenuItemController::class, 'index']);
Route::get('/menu-items/type/{type}', [MenuItemController::class, 'getByType']);
Route::get('/menu-items/label/{label}', [MenuItemController::class, 'getByLabel']);

// Rute untuk operasi keranjang belanja
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [OrderController::class, 'addToCart']);
    Route::post('/cart/remove', [OrderController::class, 'removeFromCart']);
    Route::get('/cart', [OrderController::class, 'getCart']);
    Route::post('/cart/add-one', [OrderController::class, 'addOneCartItem']);
    Route::post('/cart/minus-one', [OrderController::class, 'minusOneCartItem']);
});
