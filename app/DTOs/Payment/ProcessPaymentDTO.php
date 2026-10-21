<?php

namespace App\DTOs\Payment;

final readonly class ProcessPaymentDTO
{
    /**
     * @param  array<string, mixed>  $details
     */
    public function __construct(
        public string $orderId,
        public string $paymentMethod,
        public string $idempotencyKey,
        public array $details,
    ) {}

    /**
     * @param  array<string, mixed>  $details
     */
    public static function fromRequest(
        string $orderId,
        string $paymentMethod,
        string $idempotencyKey,
        array $details,
    ): self {
        return new self(
            orderId: $orderId,
            paymentMethod: $paymentMethod,
            idempotencyKey: $idempotencyKey,
            details: $details,
        );
    }
}
