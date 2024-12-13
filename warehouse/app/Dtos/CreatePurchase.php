<?php

namespace App\Dtos;

final readonly class CreatePurchase
{
    public function __construct(
        private string $ingredientId,
        private int    $quantity,
        private int    $orderId,
    )
    {
    }

    public function ingredientId(): string
    {
        return $this->ingredientId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function orderId(): int
    {
        return $this->orderId;
    }

    public function toArray(): array
    {
        return [
            'ingredient_id' => $this->ingredientId(),
            'quantity' => $this->quantity(),
            'order_id' => $this->orderId(),
        ];
    }
}
