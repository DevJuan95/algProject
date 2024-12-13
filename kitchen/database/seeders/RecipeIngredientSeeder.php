<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipeIngredients = [
            1 => [
                [1, 3], [7, 1], [4, 1]
            ],
            2 => [
                [10, 1], [2, 1], [7, 1]
            ],
            3 => [
                [4, 1], [10, 1], [5, 1]
            ],
            4 => [
                [9, 1], [3, 1]
            ],
            5 => [
                [4, 1], [10, 1]
            ],
            6 => [
                [1, 2], [6, 2], [7, 1], [8, 2], [10, 1]
            ]
        ];
        foreach ($recipeIngredients as $recipeId => $ingredients) {
            foreach ($ingredients as $key => $ingredient) {
                $recipeIngredients[$recipeId][$key] = [
                    'recipe_id' => $recipeId,
                    'ingredient_id' => $ingredient[0],
                    'quantity' => $ingredient[1]
                ];
            }
        }
        DB::table('recipe_ingredient')->insert(
            collect($recipeIngredients)->flatten(1)->toArray()
        );
    }
}
