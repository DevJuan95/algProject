<?php


use Database\Seeders\IngredientSeeder;

describe('GetIngredientsTest', function () {
    beforeEach(function () {
        $this->seed(IngredientSeeder::class);
        $this->response = $this->get('/v1/ingredients');
    });

    it('Test of the GetAllIngredients endpoint', function () {
        $this->response->assertStatus(200);
        expect($this->response->json())
            ->toBeArray()
            ->and($this->response->json())
            ->toHaveCount(10);
        expect($this->response->json())->each(
            fn($ingredient) => $ingredient->toHaveKeys(['id', 'name', 'stock', 'created_at', 'updated_at'])
        );
        expect($this->response->json()[0])->toMatchArray([
            'name' => 'tomato',
        ]);
    });
});
