<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartmentApiController; 
use App\Http\Controllers\Api\ProductTypeApiController; 
use App\Http\Controllers\Api\ProductApiController;


// Resursne rute za tvoje API kontrolere
Route::apiResource('departments', DepartmentApiController::class); // Koristi apiResource
Route::apiResource('product_types', ProductTypeApiController::class);
Route::apiResource('products', ProductApiController::class);