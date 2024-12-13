<?php

namespace App\Services\Marketplace;

use App\Contracts\Marketplace;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlegraIngredientBuyer implements Marketplace
{

    /**
     * @param string $name
     * @return int
     * @throws ConnectionException
     */
    public function buy(string $name): int
    {
        Log::log('info', 'Purchasing ingredient');
        $marketPlaceResponse = Http::get(env('MARKET_PLACE_URL'), [
            'ingredient' => strtolower($name),
        ]);
        if ($marketPlaceResponse->failed()) {
            throw new ConnectionException('No se logró conectar con el mercado');
        }
        $quantitySold = $marketPlaceResponse->object()->quantitySold;
        Log::info("Response from the market: {$quantitySold}");
        return (int)$quantitySold;
    }
}
