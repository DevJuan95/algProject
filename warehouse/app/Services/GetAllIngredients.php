<?php

namespace App\Services;

use App\Models\Ingredient;

final readonly class GetAllIngredients
{
    public function __construct(private Ingredient $ingredient){}

    public function handle(): array
    {
        return $this->ingredient->all()->toArray();
    }
}
