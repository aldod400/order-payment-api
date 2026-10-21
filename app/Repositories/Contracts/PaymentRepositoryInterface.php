<?php

namespace App\Repositories\Contracts;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Models\Payment;
use App\Repositories\Eloquent\PaymentRepository;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

#[Bind(PaymentRepository::class)]
interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateFiltered(PaymentFilterDTO $filter): LengthAwarePaginator;

    public function findByIdempotencyKey(string $orderId, string $idempotencyKey): ?Payment;

    public function existsForOrder(string $orderId): bool;

    public function hasSuccessfulPayment(string $orderId): bool;

    /**
     * True when the order has a payment that is pending or successful.
     */
    public function hasActivePayment(string $orderId): bool;
}
