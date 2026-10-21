<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderItem;
use App\Repositories\Contracts\OrderItemRepositoryInterface;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    public function __construct(OrderItem $model)
    {
        parent::__construct($model);
    }

    public function deleteByOrder(string $orderId): int
    {
        return $this->query()
            ->where('order_id', $orderId)
            ->delete();
    }
}
