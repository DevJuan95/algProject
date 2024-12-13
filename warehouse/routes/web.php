<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Response::JSON([
        'message' => 'Welcome to Warehouse API',
        'status' => 'connected'
    ]);
});
