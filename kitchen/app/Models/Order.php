<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    //
    use HasFactory;

    protected $fillable = ['status', 'total'];


    public function orderRecipe(): HasOne
    {
       return $this->hasOne(OrderRecipe::class, 'order_id', 'id');
    }
}
