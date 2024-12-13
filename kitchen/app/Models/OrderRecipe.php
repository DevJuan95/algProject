<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderRecipe extends Model
{
    use HasFactory;
    protected $table = 'order_recipe';
    //
    protected $fillable = ['order_id', 'recipe_name'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class,'order_ingredient')->withPivot('required_quantity');

    }
}
