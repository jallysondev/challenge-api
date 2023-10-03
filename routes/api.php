<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'getAll')
        ->middleware('auth:sanctum')
        ->name('product.getAll');

    Route::get('products/{product:code}', 'getOneById')
        ->middleware('auth:sanctum')
        ->name('product.getOneById');

    Route::put('products/{product:code}', 'update')
        ->middleware('auth:sanctum')
        ->name('product.update');

    Route::delete('products/{product:code}', 'destroy')
        ->middleware('auth:sanctum')
        ->name('product.destroy');
});
