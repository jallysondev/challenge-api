<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServerDetailsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', ServerDetailsController::class);

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'getAll')
        ->name('product.getAll');

    Route::get('products/{product:code}', 'getOneById')
        ->name('product.getOneById');

    Route::get('products-search', 'getSearch')
        ->name('product.getSearch');

    Route::put('products/{product:code}', 'update')
        ->name('product.update');

    Route::delete('products/{product:code}', 'destroy')
        ->name('product.destroy');
});
