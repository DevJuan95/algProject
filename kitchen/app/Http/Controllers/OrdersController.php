<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdersResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Order;
use App\Services\Orders\GetPaginatedOrders;
use App\Services\Orders\OrderCreator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class OrdersController
{
    //
    /**
     * @param Request $request
     * @param GetPaginatedOrders $action
     * @return JsonResponse
     */
    public function index(Request $request, GetPaginatedOrders $action): JsonResponse
    {
        $perPage = $request->query('perPage', 10);
        $page = $request->query('page', 1);
        $orders = $action->handle($perPage, $page);
        return response()->json(new PaginatedResource(OrdersResource::collection($orders)));

    }

    /**
     * @param Request $request
     * @param OrderCreator $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request, OrderCreator $action): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:5'
        ]);
        $action->handle($validated['quantity']);
        return response()->json(['message' => 'Order Created'], 201);
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function deliverIngredients(Order $order): JsonResponse
    {
        $order->update(['status' => 'delivered']);
        return response()->json(['message' => 'Ingredients confirmed'], 200);

    }
}
