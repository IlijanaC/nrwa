<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); // Laravel UI rute za login, register, itd.

Route::get('/home', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| JAVNE RUTE (dostupne neregistriranim korisnicima)
|--------------------------------------------------------------------------
*/

// Product (index i show su javni)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');//
// Napomena: Ako vaš Product model ne koristi 'id' kao route key, osigurajte da je
// getRouteKeyName() metoda definirana u Product modelu (npr. da vraća 'PRODUCT_CD')
// ili eksplicitno definirajte ključ u ruti: Route::get('/products/{product:PRODUCT_CD}', ...);

// ProductType (index i show su javni)
Route::get('/product-types', [ProductTypeController::class, 'index'])->name('product_types.index');
//Route::get('/product-types/{product_type}', [ProductTypeController::class, 'show'])->name('product_types.show');//
// Napomena za ProductType model i route key (npr. 'PRODUCT_TYPE_CD') vrijedi isto kao i za Product.


/*
|--------------------------------------------------------------------------
| RUTE KOJE ZAHTJEVAJU AUTENTIFIKACIJU (`auth` middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Departments (sve metode zahtijevaju prijavu, autorizacija dalje u kontroleru)
    // Ako Department model koristi 'DEPT_ID' kao ključ, osigurajte getRouteKeyName() ili eksplicitno navedite.
    // Route::resource('departments', DepartmentController::class);
    // Ili pojedinačno ako želite biti eksplicitni:
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Product - preostale CRUD operacije (create, store, edit, update, destroy)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // ProductType - preostale CRUD operacije
    Route::get('/product-types/create', [ProductTypeController::class, 'create'])->name('product_types.create');
    Route::post('/product-types', [ProductTypeController::class, 'store'])->name('product_types.store');
    Route::get('/product-types/{product_type}/edit', [ProductTypeController::class, 'edit'])->name('product_types.edit');
    Route::put('/product-types/{product_type}', [ProductTypeController::class, 'update'])->name('product_types.update');
    Route::delete('/product-types/{product_type}', [ProductTypeController::class, 'destroy'])->name('product_types.destroy');

});