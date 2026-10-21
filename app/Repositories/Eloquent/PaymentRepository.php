<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function paginateFiltered(PaymentFilterDTO $filter): LengthAwarePaginator
    {
        return $this->query()
            ->whereRelation('order', 'user_id', $filter->userId)
            ->when($filter->orderId, fn ($query) => $query->where('order_id', $filter->orderId))
            ->latest()
            ->paginate($filter->perPage);
    }

    public function findByIdempotencyKey(string $orderId, string $idempotencyKey): ?Payment
    {
        return $this->query()
            ->where('order_id', $orderId)
            ->where('idempotency_key', $idempotencyKey)
            ->first();
    }

    public function existsForOrder(string $orderId): bool
    {
        return $this->query()
            ->where('order_id', $orderId)
            ->exists();
    }

    public function hasSuccessfulPayment(string $orderId): bool
    {
        return $this->query()
            ->where('order_id', $orderId)
            ->where('status', PaymentStatus::SUCCESSFUL)
            ->exists();
    }

    public function hasActivePayment(string $orderId): bool
    {
        return $this->query()
            ->where('order_id', $orderId)
            ->whereIn('status', [PaymentStatus::PENDING, PaymentStatus::SUCCESSFUL])
            ->exists();
    }
}
