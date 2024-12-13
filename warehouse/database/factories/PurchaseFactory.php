<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return array(
            //
            'ingredient_id' => (new Ingredient)->inRandomOrder()->first()->id,
            'quantity' => $this->faker->numberBetween(1, 4),
            'order_id' => $this->faker->numberBetween(1, 10),
        );
    }
}
