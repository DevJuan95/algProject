<?php

use App\Services\Marketplace\AlegraIngredientBuyer;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

describe('AlegraIngredientBuyerTest', function () {
    it('Should buys the ingredients successfully', function () {
        // Mock the HTTP response
        $ingredient = 'tomato';
        Http::fake([
            env('MARKET_PLACE_URL') . "?ingredient={$ingredient}" => Http::response(['quantitySold' => 4], 200)
        ]);
        $buyer = $this->app->make(AlegraIngredientBuyer::class);
        $quantity = $buyer->buy($ingredient);
        expect($quantity)->toBeNumeric()->and($quantity)->toBe(4);
    });

    it('throws a ConnectionException on failed connection', function () {
        Http::fake([
            env('MARKET_PLACE_URL') . '?ingredient=tomato' => Http::response(null, 500)
        ]);
        $buyer = $this->app->make(AlegraIngredientBuyer::class);
        expect(fn() => $buyer->buy('Tomato', 5))->toThrow(ConnectionException::class);
    });

});
