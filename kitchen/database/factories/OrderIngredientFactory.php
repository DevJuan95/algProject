<?php

namespace Database\Factories;

use App\Models\OrderRecipe;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderIngredient>
 */
class OrderIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $recipe = Recipe::query()->inRandomOrder()->first();
        $ingredient = $recipe->ingredients->inRandomOrder()->first();
        return [
            //
            'order_recipe_id' => OrderRecipe::factory(),
            'ingredient_id' => $ingredient->id,
            'required_quantity' => $recipe->ingredients->where('id', $ingredient->id)->first()->pivot->quantity,
        ];
    }
}
