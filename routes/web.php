<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'app');
Route::view('/login', 'app');
Route::view('/manager/orders', 'app');
Route::view('/manager/orders/create', 'app');
