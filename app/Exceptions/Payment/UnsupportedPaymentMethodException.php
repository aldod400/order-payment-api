<?php

namespace App\Exceptions\Payment;

use App\Exceptions\ApiException;

final class UnsupportedPaymentMethodException extends ApiException
{
    public function __construct(string $method)
    {
        parent::__construct(__('message.unsupported_payment_method', ['method' => $method]));
    }

    public function statusCode(): int
    {
        return 422;
    }
}
