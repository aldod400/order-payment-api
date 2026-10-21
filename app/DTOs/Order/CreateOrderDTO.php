<?php

namespace App\DTOs\Order;

final readonly class CreateOrderDTO
{
    /**
     * @param  list<OrderItemDTO>  $items
     */
    public function __construct(
        public string $userId,
        public string $customerName,
        public string $customerEmail,
        public ?string $customerPhone,
        public array $items,
    ) {}

    /**
     * @param  list<OrderItemDTO>  $items
     */
    public static function fromRequest(
        string $userId,
        string $customerName,
        string $customerEmail,
        ?string $customerPhone,
        array $items,
    ): self {
        return new self(
            userId: $userId,
            customerName: $customerName,
            customerEmail: $customerEmail,
            customerPhone: $customerPhone,
            items: $items,
        );
    }
}
