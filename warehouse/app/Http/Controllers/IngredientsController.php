<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientsDeliveryRequest;
use App\Jobs\ProcessDelivery;
use App\Services\GetAllIngredients;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class IngredientsController
{
    public function __construct(){}

    public function index(GetAllIngredients $action): JsonResponse
    {
        return Response::JSON($action->handle());
    }

    public function processDelivery(IngredientsDeliveryRequest $deliveryRequest): JsonResponse
    {
        ProcessDelivery::dispatch($deliveryRequest->validated());
        return Response::JSON(['message' => 'Delivery process started']);
    }
}
