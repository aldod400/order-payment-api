<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

final class InvalidCredentialsException extends ApiException
{
    public function __construct()
    {
        parent::__construct(__('message.invalid_credentials'));
    }

    public function statusCode(): int
    {
        return 401;
    }
}
