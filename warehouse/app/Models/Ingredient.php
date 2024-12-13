<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property mixed $stock
 * @property string $name
 * @property int $id
 */
class Ingredient extends Model
{
    use HasFactory;
    //
    protected $fillable = ['name', 'stock'];
    protected $table = 'ingredients';

    public function hasEnoughStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    public function getByName(string $name): ?Ingredient
    {
        return $this->where('name', $name)->first();
    }
}
