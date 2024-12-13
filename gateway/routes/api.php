<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::any('/kitchen/{endpoint}', [GatewayController::class, 'proxyToKitchen'])
        ->where('endpoint', '.*');;
    Route::any('/warehouse/{endpoint}', [GatewayController::class, 'proxyToWarehouse'])
        ->where('endpoint', '.*');;
});





