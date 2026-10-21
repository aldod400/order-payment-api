<?php

namespace App\Exceptions\Order;

use App\Exceptions\ApiException;

final class OrderHasPaymentsException extends ApiException
{
    public function __construct()
    {
        parent::__construct(__('message.order_has_payments'));
    }

    public function statusCode(): int
    {
        return 409;
    }
}
