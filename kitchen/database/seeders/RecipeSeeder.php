<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            'tomato soup', 'chicken grilled with lemon', 'rice with chicken',
            'grilled meat', 'broaster chicken', 'chichen salad'
        ];
        foreach ($recipes as $recipe) {
            Recipe::create(['name' => $recipe]);
        }
    }
}
