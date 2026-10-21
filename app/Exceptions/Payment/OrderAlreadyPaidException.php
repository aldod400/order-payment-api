<?php

namespace App\Exceptions\Payment;

use App\Exceptions\ApiException;

final class OrderAlreadyPaidException extends ApiException
{
    public function __construct()
    {
        parent::__construct(__('message.order_already_paid'));
    }

    public function statusCode(): int
    {
        return 409;
    }
}
