<?php

namespace App\Services\Purchases;

use App\Contracts\Marketplace;
use App\Dtos\CreatePurchase;
use App\Exceptions\MaximumRetriesException;
use App\Models\Ingredient;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PurchasesProcessor
{
    private array $delivery;

    public function __construct(
        private readonly Ingredient  $model,
        private readonly Marketplace $marketplace
    ){}

    /**
     * @param array $delivery
     */
    public function setDeliver(array $delivery): void
    {
        $this->delivery = $delivery;
    }

    /**
     * @param array $delivery
     * @throws Exception
     */
    public function run(array $delivery): void
    {
        $this->setDeliver($delivery);
        $deliveryIngredients = $delivery['ingredients'];

        foreach ($deliveryIngredients as $deliveryIngredient) {
            DB::transaction(function () use ($deliveryIngredient) {
                $ingredient = $this->model->query()
                    ->where('name', $deliveryIngredient['name'])
                    ->lockForUpdate()
                    ->first();

                if (!$ingredient) {
                    throw new Exception("Ingredient {$deliveryIngredient['name']} not found");
                }

                if (!$ingredient->hasEnoughStock($deliveryIngredient['quantity'])) {
                    Log::info("Not enough stock of {$ingredient->name} for order {$this->delivery['orderId']}");
                    $this->refillStock($ingredient, $deliveryIngredient['quantity']);
                }
            });
        }

        Log::info('Processing delivery for order ' . $delivery['orderId']);
        $this->deliverIngredients($delivery);
    }

    /**
     * @param Ingredient $ingredient
     * @param int $requiredQuantity
     * @throws MaximumRetriesException
     **/
    private function refillStock(Ingredient $ingredient, int $requiredQuantity): void
    {
        $maxRetries = 3;
        $retryCount = 0;
        $ingredient->refresh();
        while ($requiredQuantity > $ingredient->stock) {
            try {
                $quantityBought = $this->marketplace->buy(strtolower($ingredient->name));
                if ($this->isSuccessfulPurchase($quantityBought)) {
                    $this->registerPurchase($ingredient, $quantityBought);
                }
                $ingredient->stock += $quantityBought;
                $retryCount = 0;
            } catch (ConnectionException $e) {
                Log::error("Connection error while buying {$ingredient->name}: " . $e->getMessage());
                if (++$retryCount > $maxRetries) {
                    throw new MaximumRetriesException(
                        "Maximum retries reached while buying {$ingredient->name}"
                    );
                }
            }
        }
        $ingredient->save();
    }

    /**
     * @param array $delivery
     * @throws Exception
     * @throws ConnectionException
     */
    private function deliverIngredients(array $delivery): void
    {
        $ingredients = $delivery['ingredients'];
        $order = $delivery['orderId'];
        $url = env('KITCHEN_URL');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("{$url}/v1/order/{$order}/mark-delivered");

        if ($response->failed()) {
            throw new ConnectionException('Error delivering ingredients to kitchen');
        }

        DB::transaction(function () use ($ingredients) {
            foreach ($ingredients as $ingredient) {
                $ingredientModel = $this->model->query()
                    ->where('name', $ingredient['name'])
                    ->lockForUpdate()
                    ->first();

                if ($ingredientModel) {
                    $ingredientModel->stock -= $ingredient['quantity'];
                    if ($ingredientModel->stock < 0) {
                        throw new Exception(
                            "Stock inconsistency detected for {$ingredientModel->name}
                            for order {$this->delivery['orderId']}"
                        );
                    }
                    $ingredientModel->save();
                }
            }
        });

        Log::info("Ingredients delivered successfully.");
    }

    private function registerPurchase(Ingredient $ingredient, int $quantity): void
    {
        app(PurchaseCreator::class)->persist(
            new CreatePurchase(
                ingredientId: $ingredient->id,
                quantity: $quantity,
                orderId: $this->delivery['orderId']
            )
        );
    }

    private function isSuccessfulPurchase(int $quantityBought): bool
    {
        return $quantityBought !== 0;
    }
}
