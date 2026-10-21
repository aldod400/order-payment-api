<?php

namespace App\DTOs\Payment;

/**
 * What a gateway needs to charge; it never sees Eloquent models.
 */
final readonly class ChargeDTO
{
    /**
     * @param  array<string, mixed>  $details
     */
    public function __construct(
        public string $orderId,
        public string $amount,
        public string $currency,
        public string $idempotencyKey,
        public array $details,
    ) {
    }
}
