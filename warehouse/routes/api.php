<?php

use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\PurchasesController;
use Illuminate\Support\Facades\Route;

Route::get('/ingredients', [IngredientsController::class, 'index']);
Route::post('/delivery', [IngredientsController::class, 'processDelivery']);
Route::get('/purchases', [PurchasesController::class, 'index']);
