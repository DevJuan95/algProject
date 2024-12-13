<?php

namespace App\Services\Recipes;

use App\Models\Recipe;
use Illuminate\Support\Collection;

class GetAllRecipes
{
    public function __construct(private readonly Recipe $recipe)
    {
    }

    public function handle(): Collection
    {
        return $this->recipe->with('ingredients')->get();
    }
}
