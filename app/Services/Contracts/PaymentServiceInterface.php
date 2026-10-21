<?php

namespace App\Services\Contracts;

use App\DTOs\Payment\PaymentFilterDTO;
use App\DTOs\Payment\ProcessPaymentDTO;
use App\Models\Payment;
use App\Services\Implementations\PaymentService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

#[Bind(PaymentService::class)]
interface PaymentServiceInterface
{
    public function list(PaymentFilterDTO $filter): LengthAwarePaginator;

    /**
     * Charge the order through the chosen gateway. Repeating the same
     * idempotency key returns the original payment instead of charging again.
     */
    public function process(ProcessPaymentDTO $dto): Payment;
}
