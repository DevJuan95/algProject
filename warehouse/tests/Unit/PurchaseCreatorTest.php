<?php

use App\Dtos\CreatePurchase;
use App\Services\Purchases\PurchaseCreator;
use Database\Seeders\IngredientSeeder;

describe('PurchaseCreator', function () {
    it('should register a purchase into the model', function () {
        $this->seed(IngredientSeeder::class);
        $purchaseCreator = $this->app->make(PurchaseCreator::class);

        $createPurchase = new CreatePurchase(
            ingredientId: 1,
            quantity: 10,
            orderId: 100
        );
        $purchaseCreator->persist($createPurchase);

        $this->assertDatabaseHas('purchases', [
            'ingredient_id' => 1,
            'quantity' => 10,
            'order_id' => 100
        ]);
    });
});
