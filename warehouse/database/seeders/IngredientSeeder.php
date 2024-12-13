<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class IngredientSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            'tomato',
            'lemon',
            'potato',
            'rice',
            'ketchup',
            'lettuce',
            'onion',
            'cheese',
            'meat',
            'chicken'
        ];
        foreach ($ingredients as $key => $ingredient) {
            $ingredients[$key] = [
                'name' => $ingredient,
                'stock' => 5
            ];
        }
        Ingredient::factory()->createMany(
            $ingredients
        );
    }
}
