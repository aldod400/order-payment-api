<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Order\OrderFilterDTO;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function paginateForUser(OrderFilterDTO $filter): LengthAwarePaginator
    {
        return $this->query()
            ->with('items')
            ->where('user_id', $filter->userId)
            ->when($filter->status, fn ($query) => $query->where('status', $filter->status))
            ->latest()
            ->paginate($filter->perPage);
    }

    public function findForUpdate(string $id): Order
    {
        return $this->query()
            ->lockForUpdate()
            ->findOrFail($id);
    }
}
