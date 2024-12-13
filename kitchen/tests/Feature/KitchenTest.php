<?php

use App\Events\OrderCreated;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

describe('Kitchen E2E test', function () {
    beforeEach(function () {
        Event::fake();
        $this->seed(DatabaseSeeder::class);
        Http::fake(
            [
                '*' => Illuminate\Http\Client\Factory::response(
                    status: 200,
                ),
            ]
        );
        //refresh the database
        uses(RefreshDatabase::class);
    });
    it('should create 1 orders and
            dispatch the event Order created', function () {
        $response = $this->json('POST', '/v1/orders', [
            'quantity' => 1,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_recipe', 1);
        Event::assertDispatched(OrderCreated::class);
    });

    it('should throw an error when
        creating an order with 10 quantity', function () {
        $response = $this->json('POST', '/v1/orders', [
            'quantity' => 10,
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_recipe', 0);
        Event::assertNotDispatched(OrderCreated::class);
    });

    it('should return a list of orders', function () {
        $this->json('POST', '/v1/orders', [
            'quantity' => 1,
        ]);
        $response = $this->json('GET', '/v1/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'created_at',
                    'recipe',
                    'ingredients' => [
                        '*' => [
                            'id',
                            'name',
                            'quantity',
                        ]
                    ],
                ],
            ],
        ]);
    });

    it('Should paginate the orders', function () {
        for($i = 0; $i < 3; $i++) {
            $this->json('POST', '/v1/orders', [
                'quantity' => 5,
            ]);
        }
        $response = $this->json('GET', '/v1/orders?page=2&perPage=2');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'created_at',
                    'recipe',
                    'ingredients' => [
                        '*' => [
                            'id',
                            'name',
                            'quantity',
                        ]
                    ],
                ],
            ],
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total'
            ],
        ]);
    });

    it('should return a 404 when trying
            to get an order that does not exist', function () {
        $response = $this->json('GET', '/v1/orders/1');
        $response->assertStatus(404);
    });

    it('should return the list of recipes', function () {
        $response = $this->json('GET', '/v1/recipes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'ingredients' => [
                    '*' => [
                        'id',
                        'name',
                        'quantity',
                    ]
                ],
            ],
        ]);
    });

    it('should mark as delivered ', function () {
        $this->json('POST', '/v1/orders', [
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'status' => 'created',
        ]);
        $response = $this->json('POST', '/v1/order/1/mark-delivered');
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'status' => 'delivered',
        ]);
    });
});
