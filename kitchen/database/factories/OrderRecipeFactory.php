<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderRecipe>
 */
class OrderRecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $recipe = Recipe::query()->inRandomOrder()->first();
        return [
            //
            'order_id' => Order::factory(),
            'name' => $recipe->name,
        ];
    }
}
