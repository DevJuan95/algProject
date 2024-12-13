<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = JWTAuth::fromUser($this->user);
});

describe('Gateway E2E test', function () {
    it('should get the kitchen data successfully', function () {
        Http::fake([
            'http://kitchenwebserver/v1/*' => Http::response(['data' => 'kitchen data'], 200)
        ]);
        $response = $this
            ->withHeader('Authorization', "Bearer {$this->token}")
            ->get('/kitchen/orders');
        $response->assertStatus(200);
        $response->assertJsonFragment(['data' => 'kitchen data']);
    });

    it('should get the warehouse data successfully', function () {
        Http::fake([
            'http://warehousewebserver/v1/*' => Http::response(['data' => 'warehouse data'], 200)
        ]);
        $response = $this
            ->withHeader('Authorization', "Bearer {$this->token}")
            ->get('/warehouse/orders');
        $response->assertStatus(200);
        $response->assertJsonFragment(['data' => 'warehouse data']);
    });
});

