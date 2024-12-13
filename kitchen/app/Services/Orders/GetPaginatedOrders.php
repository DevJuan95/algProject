<?php

namespace App\Services\Orders;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPaginatedOrders
{
    public function __construct()
    {
    }

    public function handle(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return Order::with('orderRecipe.ingredients')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
