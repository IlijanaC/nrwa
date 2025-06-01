<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartmentApiController; 
use App\Http\Controllers\Api\ProductTypeApiController; 
use App\Http\Controllers\Api\ProductApiController;


// Grupira sve API rute koje zahtijevaju HTTP Basic autentifikaciju
// Svaki zahtjev prema ovim rutama morat će sadržavati Authorization: Basic zaglavlje.
Route::middleware('auth.basic')->group(function () {
    // Resursne rute za tvoje API kontrolere
    Route::apiResource('departments', DepartmentApiController::class);
    Route::apiResource('product_types', ProductTypeApiController::class);
    Route::apiResource('products', ProductApiController::class);
});