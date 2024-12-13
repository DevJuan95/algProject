<?php

use Database\Seeders\IngredientSeeder;
use Database\Seeders\PurchaseSeeder;

beforeEach(function (){
    $this->endpoint = '/v1/purchases';
    $this->seed(IngredientSeeder::class);
    $this->seed(PurchaseSeeder::class);
});
describe('GET /purchases', function (){
    it('should return the purchase history', function (){
        $response = $this->get($this->endpoint . '?page=1&per_page=10');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'quantity',
                    'created_at',
                    'order_id',
                    'ingredient' => [
                        'id',
                        'name',
                    ],
                ],
            ],
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);
        expect($response->json('data'))->toHaveCount(10);
    });
    it('should fetch page 2', function (){
        $response = $this->get($this->endpoint . '?page=2&per_page=10');
        $response->assertStatus(200);
        expect($response->json('meta.current_page'))->toBe(2);
        expect($response->json('data'))->toHaveCount(10);
    });

    it('should return only requested amount per page', function (){
        $response = $this->get($this->endpoint . '?page=1&per_page=5');
        $response->assertStatus(200);
        expect($response->json('data'))->toHaveCount(5);
    });
});
