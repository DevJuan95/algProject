<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GatewayController
{
    private array $services = [
        'kitchen' => 'http://kitchenWebserver/v1/',
        'warehouse' => 'http://warehouseWebserver/v1/',
    ];

    /**
     * @param $endpoint
     * @param Request $request
     * @return Application|Response|ResponseFactory
     * @throws Exception
     */
    public function proxyToKitchen(
        $endpoint,
        Request $request
    ): Application|Response|ResponseFactory
    {
        return $this->proxyRequest('kitchen', $endpoint, $request);
    }

    /**
     * @param $endpoint
     * @param Request $request
     * @return Application|Response|ResponseFactory
     * @throws Exception
     */
    public function proxyToWarehouse($endpoint, Request $request): Application|Response|ResponseFactory
    {
        return $this->proxyRequest('warehouse', $endpoint, $request);
    }

    /**
     * @param string $service
     * @param string $endpoint
     * @param Request $request
     * @return Application|Response|ResponseFactory
     * @throws Exception
     */
    private function proxyRequest(
        string  $service,
        string  $endpoint,
        Request $request
    ): Application|Response|ResponseFactory
    {
        $url = $this->services[$service] . $endpoint;
        try {
            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'query' => $request->query(),
                    'json' => $request->all(),
                ]);
        } catch (ConnectionException $e) {
            Log::error($e);
            return response(['error' => 'Service unavailable.'], 503);
        }

        return response($response->body(), $response->status(), $response->headers());
    }
}
