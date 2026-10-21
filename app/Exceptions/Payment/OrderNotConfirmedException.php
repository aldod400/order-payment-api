<?php

namespace App\Exceptions\Payment;

use App\Exceptions\ApiException;

final class OrderNotConfirmedException extends ApiException
{
    public function __construct()
    {
        parent::__construct(__('message.order_not_confirmed'));
    }

    public function statusCode(): int
    {
        return 422;
    }
}
