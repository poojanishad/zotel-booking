<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('search');
});
Route::get('/inventory-view', function () {
    return view('inventory');
});
Route::get('/discounts', function () {
    return view('discount');
});