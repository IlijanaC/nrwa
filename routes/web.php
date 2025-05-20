<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController; 
use App\Http\Controllers\ProductTypeController; 
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\HomeController; 

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home'); // Koristi HomeController::class

// OVO SU LINIJE KOJE NEDOSTAJU!
// One govore Laravelu da koristi tvoje web kontrolere za CRUD operacije u pregledniku.
Route::resource('departments', DepartmentController::class);
Route::resource('product_types', ProductTypeController::class);
Route::resource('products', ProductController::class);
