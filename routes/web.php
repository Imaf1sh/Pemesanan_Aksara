<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ViewController::class, 'customer'])->name('customer');
Route::get('/pos', [ViewController::class, 'pos'])->name('pos');
Route::get('/kasir', [ViewController::class, 'kasir'])->name('kasir');
