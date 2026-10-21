<?php

namespace App\DTOs\Order;

final readonly class OrderItemDTO
{
    public function __construct(
        public string $productName,
        public int $quantity,
        public string $price,
    ) {
    }

    public static function fromRequest(string $productName, int $quantity, string $price): self
    {
        return new self(
            productName: $productName,
            quantity: $quantity,
            price: $price,
        );
    }

    /**
     * @param  list<array{product_name: string, quantity: int|string, price: int|float|string}>  $items
     * @return list<self>
     */
    public static function collectionFromRequest(array $items): array
    {
        return array_map(fn (array $item) => self::fromRequest(
            productName: $item['product_name'],
            quantity: (int) $item['quantity'],
            price: (string) $item['price'],
        ), $items);
    }
}
