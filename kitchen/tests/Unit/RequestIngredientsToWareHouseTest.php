<?php

use App\Dtos\DeliveryPayload;
use App\Events\OrderCreated;
use App\Listeners\RequestIngredientsToWareHouse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

describe('RequestIngredientsToWareHouseTest', function () {
    beforeEach(function () {
        Event::fake();
        config()->set('queue.default', 'sync');

        Http::fake([
            'http://warehousewebserver/v1/delivery' => Http::response(['success' => true], 200),
        ]);
    });

    it('should request ingredients to the warehouse', function () {
        $listener = app()->make(RequestIngredientsToWareHouse::class);
        $deliveryPayload = new DeliveryPayload(1, ['tomato' => 1, 'onion' => 1]);

        $listener->handle(new OrderCreated($deliveryPayload));

        Http::assertSent(function ($request) use ($deliveryPayload) {
            return $request->url() === 'http://warehousewebserver/v1/delivery' &&
                $request->method() === 'POST' &&
                $request->body() === json_encode($deliveryPayload->toArray());
        });
    });

});
