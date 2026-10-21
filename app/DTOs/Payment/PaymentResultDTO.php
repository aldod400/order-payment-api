<?php

namespace App\DTOs\Payment;

use App\Enums\PaymentStatus;

/**
 * What a gateway returns after a charge attempt.
 */
final readonly class PaymentResultDTO
{
    /**
     * @param  array<string, mixed>  $response  Safe-to-store gateway reply (no card numbers).
     */
    private function __construct(
        public PaymentStatus $status,
        public ?string $transactionId,
        public array $response,
    ) {}

    /**
     * @param  array<string, mixed>  $response
     */
    public static function successful(string $transactionId, array $response = []): self
    {
        return new self(PaymentStatus::SUCCESSFUL, $transactionId, $response);
    }

    /**
     * @param  array<string, mixed>  $response
     */
    public static function failed(array $response = []): self
    {
        return new self(PaymentStatus::FAILED, null, $response);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::SUCCESSFUL;
    }
}
