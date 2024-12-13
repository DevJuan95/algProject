<?php

use App\Jobs\ProcessDelivery;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->delivery = [
        'orderId' => 1,
        'recipeName' => 'Sopa de tomate',
        'ingredients' => [
            ['name' => 'tomato', 'quantity' => 2],
            ['name' => 'onion', 'quantity' => 1],
        ]
    ];
});

describe('ProcessDeliveryTest', function () {
    it('pushes the job onto the queue', function () {
        Queue::fake();
        ProcessDelivery::dispatch($this->delivery);
        Queue::assertPushed(ProcessDelivery::class, function ($job) {
            return $job->delivery() === $this->delivery;
        });
    });
});
