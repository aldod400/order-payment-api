<?php

namespace App\Exceptions;

use Exception;

/**
 * Base class for business-rule errors returned to API clients.
 * Extend it and declare the HTTP status code; ApiExceptionRenderer does the rest.
 */
abstract class ApiException extends Exception
{
    abstract public function statusCode(): int;
}
