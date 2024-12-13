<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\RecipesController;
use Illuminate\Support\Facades\Route;

Route::get('/recipes', [RecipesController::class, 'index']);
Route::get('/orders', [OrdersController::class, 'index']);
Route::post('/order/{order}/mark-delivered',
    [OrdersController::class, 'deliverIngredients']
);
Route::post('/orders', [OrdersController::class, 'store']);
