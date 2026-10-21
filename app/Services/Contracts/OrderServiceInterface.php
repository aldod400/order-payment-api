<?php

namespace App\Services\Contracts;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Models\Order;
use App\Services\Implementations\OrderService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

#[Bind(OrderService::class)]
interface OrderServiceInterface
{
    public function list(OrderFilterDTO $filter): LengthAwarePaginator;

    public function create(CreateOrderDTO $dto): Order;

    public function update(Order $order, UpdateOrderDTO $dto): Order;

    public function delete(Order $order): void;
}
