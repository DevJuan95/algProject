<?php

namespace App\Services\Orders;

use App\Dtos\DeliveryPayload;
use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\OrderRecipe;
use App\Models\Recipe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderCreator
{
    public function __construct(
        private Recipe $recipe
    )
    {
    }

    /**
     * @param int $quantityOfMeals
     * @throws Throwable
     */
    public function handle(int $quantityOfMeals): void
    {
        $recipes = collect();
        for ($i = 0; $i < $quantityOfMeals; $i++) {
            $recipes->push($this->recipe->inRandomOrder()->first());
        }
        foreach ($recipes as $recipe) {
            DB::beginTransaction();
            $order = Order::create([
                'total' => $quantityOfMeals
            ]);
            $orderRecipe = $order->orderRecipe()->create([
                'order_id' => $order->id,
                'recipe_name' => $recipe->name
            ]);
            $ingredients = $this->attachIngredients($recipe, $orderRecipe);
            DB::commit();
            $payload = new DeliveryPayload($order->id, $ingredients);
            OrderCreated::dispatch($payload);
        }
    }

    /**
     * @param Recipe $recipe
     * @param OrderRecipe $orderRecipe
     * @return array<string,int>
     */
    private function attachIngredients(Recipe $recipe, OrderRecipe $orderRecipe): array
    {
        $ingredients = [];
        foreach ($recipe->ingredients as $ingredient) {
            $orderRecipe->ingredients()->attach($ingredient->id, [
                'required_quantity' => $ingredient->pivot->quantity
            ]);
            $ingredients[$ingredient->name] = $ingredient->pivot->quantity;
        }
        return $ingredients;
    }
}
