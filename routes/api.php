<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// API Routes for products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// API Routes for users
Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
Route::get('/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'show']);
Route::post('/users', [\App\Http\Controllers\Api\UserController::class, 'store']);
Route::match(['put','patch'], '/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'update']);
Route::delete('/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);