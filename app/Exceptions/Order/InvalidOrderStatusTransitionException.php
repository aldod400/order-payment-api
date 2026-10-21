<?php

namespace App\Exceptions\Order;

use App\Enums\OrderStatus;
use App\Exceptions\ApiException;

final class InvalidOrderStatusTransitionException extends ApiException
{
    public function __construct(OrderStatus $from, OrderStatus $to)
    {
        parent::__construct(__('message.invalid_order_status_transition', [
            'from' => $from->value,
            'to' => $to->value,
        ]));
    }

    public function statusCode(): int
    {
        return 422;
    }
}
