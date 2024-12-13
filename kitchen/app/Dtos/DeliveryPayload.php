<?php

namespace App\Dtos;

class DeliveryPayload
{
    private array $formattedIngredients = [];

    /**
     * DeliveryPayload constructor.
     * @param int $orderId
     * @param array<string, int> $ingredients
     */
    public function __construct(
        private readonly int $orderId,
        readonly array       $ingredients
    )
    {
        foreach ($ingredients as $ingredientName => $quantity) {
            $this->formattedIngredients[] = [
                'name' => $ingredientName,
                'quantity' => $quantity
            ];
        }
    }

    public function orderId(): int
    {
        return $this->orderId;
    }

    public function ingredients(): array
    {
        return $this->formattedIngredients;
    }

    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'ingredients' => $this->formattedIngredients
        ];
    }
}
