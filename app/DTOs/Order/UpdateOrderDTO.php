<?php

namespace App\DTOs\Order;

use App\Enums\OrderStatus;

final readonly class UpdateOrderDTO
{
    /**
     * @param  list<OrderItemDTO>|null  $items
     */
    public function __construct(
        public ?string $customerName,
        public ?string $customerEmail,
        public ?string $customerPhone,
        public ?OrderStatus $status,
        public ?array $items,
    ) {}

    /**
     * @param  list<OrderItemDTO>|null  $items
     */
    public static function fromRequest(
        ?string $customerName,
        ?string $customerEmail,
        ?string $customerPhone,
        ?OrderStatus $status,
        ?array $items,
    ): self {
        return new self(
            customerName: $customerName,
            customerEmail: $customerEmail,
            customerPhone: $customerPhone,
            status: $status,
            items: $items,
        );
    }
}
