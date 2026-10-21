<?php

namespace App\DTOs\Payment;

final readonly class PaymentFilterDTO
{
    public function __construct(
        public string $userId,
        public ?string $orderId,
        public int $perPage,
    ) {}

    public static function fromRequest(string $userId, ?string $orderId, int $perPage): self
    {
        return new self(
            userId: $userId,
            orderId: $orderId,
            perPage: $perPage,
        );
    }
}
