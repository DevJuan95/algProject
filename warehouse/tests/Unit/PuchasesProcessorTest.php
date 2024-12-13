<?php

use App\Contracts\Marketplace;
use App\Dtos\CreatePurchase;
use App\Exceptions\MaximumRetriesException;
use App\Models\Ingredient;
use App\Services\Purchases\PurchaseCreator;
use App\Services\Purchases\PurchasesProcessor;
use Database\Seeders\IngredientSeeder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;

describe('PurchaseProcessor', function () {

    function depleteStock(string $ingredientName): void
    {
        $ingredient = Ingredient::where('name', $ingredientName)->first();
        $ingredient->stock = 0;
        $ingredient->save();
    }

    function setMarketplaceExpectations(array $delivery, MockInterface $fakeMarketplace): void
    {
        foreach ($delivery['ingredients'] as $ingredient) {
            $fakeMarketplace->shouldReceive('buy')
                ->with($ingredient['name'])
                ->times($ingredient['quantity'])
                ->andReturn(1);
        }
    }

    beforeEach(function () {
        $this->seed(IngredientSeeder::class);
        Event::fake();
        Log::spy();
        Http::fake([
            '*' => Http::response(['data' => 'data'], 200)
        ]);
        $this->fakeMarketplace = Mockery::mock(Marketplace::class);
        $this->processor = new PurchasesProcessor(
            model: $this->app->make(Ingredient::class),
            marketplace: $this->fakeMarketplace
        );
    });

    it('Should call the Marketplace
        to buy the ingredients until the stock is enough', function () {
        //Arrange
        $delivery = [
            'orderId' => 1,
            'ingredients' => [
                ['name' => 'tomato', 'quantity' => 2],
                ['name' => 'onion', 'quantity' => 1],
            ]
        ];
        $this->instance(Marketplace::class, $this->fakeMarketplace);
        foreach ($delivery['ingredients'] as $ingredient) {
            depleteStock($ingredient['name']);
        }
        setMarketplaceExpectations($delivery, $this->fakeMarketplace);

        //Act
        $this->processor->run($delivery);

        //Assert
        $this->fakeMarketplace->shouldHaveReceived('buy')
            ->with($delivery['ingredients'][0]['name'])
            ->twice();
        $this->fakeMarketplace->shouldHaveReceived('buy')
            ->with($delivery['ingredients'][1]['name'])
            ->once();

    });

    it('should register the purchase if it is successful', function () {
        //arrange
        $delivery = [
            'orderId' => 1,
            'recipeName' => 'Sopa de tomate',
            'ingredients' => [
                ['name' => 'tomato', 'quantity' => 2],
            ]
        ];
        $fakePurchaseCreator = Mockery::mock(PurchaseCreator::class);
        $this->instance(Marketplace::class, $this->fakeMarketplace);
        $this->instance(PurchaseCreator::class, $fakePurchaseCreator);
        foreach ($delivery['ingredients'] as $ingredient) {
            depleteStock($ingredient['name']);
        }
        $fakePurchaseCreator
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($arg) use ($delivery) {
                return $arg instanceof CreatePurchase &&
                    $arg->ingredientId() == 1 &&
                    $arg->quantity() === 1 &&
                    $arg->orderId() === $delivery['orderId'];
            }))
            ->atLeast()
            ->times(1);
        setMarketplaceExpectations($delivery, $this->fakeMarketplace);

        //act
        $this->processor->run($delivery);

        //assert
        $fakePurchaseCreator->shouldHaveReceived('persist')
            ->with(Mockery::on(function ($arg) use ($delivery) {
                return $arg instanceof CreatePurchase &&
                    $arg->ingredientId() == 1 &&
                    $arg->quantity() === 1 &&
                    $arg->orderId() === $delivery['orderId'];
            }));
    });


    it('handles ConnectionException and continue the process', function () {
        $delivery = [
            'orderId' => 1,
            'recipeName' => 'Sopa de tomate',
            'ingredients' => [
                ['name' => 'tomato', 'quantity' => 2],
                ['name' => 'onion', 'quantity' => 1],
            ]
        ];
        $this->instance(Marketplace::class, $this->fakeMarketplace);
        // Set the stock of the ingredients to 0
        foreach ($delivery['ingredients'] as $ingredient) {
            depleteStock($ingredient['name']);
        }

        $this->fakeMarketplace->shouldReceive('buy')
            ->with('tomato')
            ->once()
            ->andThrow(new ConnectionException('Generic error'));

        $this->fakeMarketplace->shouldReceive('buy')
            ->with('tomato')
            ->times(2)
            ->andReturn(1);
        $this->fakeMarketplace->shouldReceive('buy')
            ->with('onion')
            ->andReturn(1);

        $this->processor->run($delivery);
        // Assert

        // We expect it to be called 3 times because the first time
        // it will throw an exception
        // The process will continue and buy the ingredient again
        $this->fakeMarketplace->shouldHaveReceived('buy')
            ->with('tomato')
            ->times(3);

        $this->fakeMarketplace->shouldHaveReceived('buy')
            ->with('onion')
            ->once();

    });

    it('should throw MaximumRetriesException after exceeding the maximum retries', function () {
        $delivery = [
            'orderId' => 1,
            'recipeName' => 'Sopa de tomate',
            'ingredients' => [
                ['name' => 'tomato', 'quantity' => 2],
                ['name' => 'onion', 'quantity' => 1],
            ]
        ];
        $this->instance(Marketplace::class, $this->fakeMarketplace);
        // Set the stock of the ingredients to 0
        foreach ($delivery['ingredients'] as $ingredient) {
            depleteStock($ingredient['name']);
        }

        $this->fakeMarketplace->shouldReceive('buy')
            ->with('tomato')
            ->times(4)
            ->andThrow(new ConnectionException('Generic error'));

        $this->fakeMarketplace->shouldReceive('buy')
            ->with('onion')
            ->andReturn(1);

        $this->expectException(MaximumRetriesException::class);
        $this->processor->run($delivery);
    });
});
