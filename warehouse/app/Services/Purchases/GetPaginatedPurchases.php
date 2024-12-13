<?php

namespace App\Services\Purchases;

use App\Models\Purchase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPaginatedPurchases
{
    public function __construct(
        private readonly Purchase $model
    )
    {
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function handle(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        // this could be enhanced by using a repository pattern
        return $this->model->with('ingredient')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(
                $perPage, ['*'],
                'page',
                $page
            );
    }
}
