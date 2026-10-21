<?php

namespace App\Repositories\Contracts;

use App\Repositories\Eloquent\OrderItemRepository;
use Illuminate\Container\Attributes\Bind;

#[Bind(OrderItemRepository::class)]
interface OrderItemRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteByOrder(string $orderId): int;
}
