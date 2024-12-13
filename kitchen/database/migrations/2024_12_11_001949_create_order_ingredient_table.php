<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_recipe_id')->constrained('order_recipe')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('required_quantity');
            $table->timestamps();
        });
    }
};
