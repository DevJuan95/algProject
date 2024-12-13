<?php

namespace App\Services\Purchases;

use App\Dtos\CreatePurchase;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;

/**
 * Class PurchaseRegistration
 * @package App\Services\Purchases
 */
class PurchaseCreator
{
    public function __construct(private Purchase $model){}

    /**
     * @param CreatePurchase $purchase
     * @return void
     */
    public function persist(CreatePurchase $purchase): void
    {
        Log::info('Registering purchase into model');
        $this->model->create($purchase->toArray());
    }
}
