<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedResource;
use App\Http\Resources\PurchaseResource;
use App\Services\Purchases\GetPaginatedPurchases;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchasesController
{
    public function index(Request $request, GetPaginatedPurchases $action): JsonResponse
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $purchaseHistory = $action->handle($page, $perPage);
        return (new PaginatedResource(PurchaseResource::collection($purchaseHistory)))->response();
    }
}
