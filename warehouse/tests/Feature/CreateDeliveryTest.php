<?php

/**
 * @group create-delivery
 * @group delivery
 * @group api
 * We can enhance this test with the Mother pattern to create the request body
 */

use App\Jobs\ProcessDelivery;
use Database\Seeders\IngredientSeeder;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(IngredientSeeder::class);
    $this->url = '/v1/delivery';
    $this->deliveryRequestBody = [
        'orderId' => 1,
        'ingredients' => [
            ['name' => 'tomato', 'quantity' => 2],
            ['name' => 'onion', 'quantity' => 1],
        ]
    ];
    Queue::fake();
});
describe('Creation of a delivery:', function () {
    it('Should return a validation error if request data is not correct', function () {
        $response = $this->postJson($this->url,
            [
                'orderId' => 'thisisnotanumber',
                'ingredients' => [
                    ['name' => 'tomato', 'quantity' => 0],
                    ['name' => 'onion', 'quantity' => 0],
                ]
            ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(
            [
                'orderId',
                'ingredients.0.quantity',
                'ingredients.1.quantity'
            ]
        );
    });

    it('Should return a success message if request data is correct', function () {
        $response = $this->postJson($this->url, $this->deliveryRequestBody);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Delivery process started']);
        Queue::assertPushed(ProcessDelivery::class);
    });

    it('Should return 422 if the ingredient name is invalid', function(){
        $response = $this->postJson($this->url,
            [
                'orderId' => 1,
                'ingredients' => [
                    ['name' => 'invalid', 'quantity' => 1],
                ]
            ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ingredients.0.name']);
    });
});
