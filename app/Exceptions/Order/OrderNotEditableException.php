<?php

namespace App\Exceptions\Order;

use App\Exceptions\ApiException;

final class OrderNotEditableException extends ApiException
{
    public function __construct()
    {
        parent::__construct(__('message.order_not_editable'));
    }

    public function statusCode(): int
    {
        return 409;
    }
}
