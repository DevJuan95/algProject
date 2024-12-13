<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RequestIngredientsToWarehouse
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(OrderCreated $event): void
    {
        Log::info('Requesting ingredients to warehouse', $event->payload->toArray());
        $url = env('WAREHOUSE_URL');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post("{$url}/v1/delivery", $event->payload->toArray());
        Log::info($response);
        if($response->failed()) {
            Log::error('Error requesting ingredients to warehouse', $response->json());
            throw new Exception('Error requesting ingredients to warehouse');
        }
    }
}
