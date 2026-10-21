<?php

namespace App\Repositories\Contracts;

use App\DTOs\Order\OrderFilterDTO;
use App\Models\Order;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

#[Bind(OrderRepository::class)]
interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateForUser(OrderFilterDTO $filter): LengthAwarePaginator;

    /**
     * Load the order and lock its row until the current transaction ends.
     */
    public function findForUpdate(string $id): Order;
}
