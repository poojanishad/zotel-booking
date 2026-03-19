<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscountController;

Route::get('/search', [SearchController::class, 'search']);
Route::get('/inventory', [InventoryController::class, 'index']);
Route::post('/update-inventory', [InventoryController::class, 'update']);
Route::get('/discounts', [DiscountController::class, 'index']);
Route::post('/discounts', [DiscountController::class, 'store']);