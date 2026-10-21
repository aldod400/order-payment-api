<?php

namespace App\DTOs\Order;

use App\Enums\OrderStatus;

final readonly class OrderFilterDTO
{
    public function __construct(
        public string $userId,
        public ?OrderStatus $status,
        public int $perPage,
    ) {
    }

    public static function fromRequest(string $userId, ?OrderStatus $status, int $perPage): self
    {
        return new self(
            userId: $userId,
            status: $status,
            perPage: $perPage,
        );
    }
}
