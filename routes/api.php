<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/shift-status', [ShiftController::class, 'status']);
Route::post('/open-shift', [ShiftController::class, 'open']);
Route::post('/close-shift', [ShiftController::class, 'close']);
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/orders/update-status', [OrderController::class, 'updateStatus']);
