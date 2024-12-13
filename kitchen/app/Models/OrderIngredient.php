<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderIngredient extends Model
{
    //
    use HasFactory;
    protected $fillable = ['order_recipe_id', 'ingredient_id', 'required_quantity'];

    public function orderRecipe(): BelongsTo
    {
        return $this->belongsTo(OrderRecipe::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
