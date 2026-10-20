<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

/**
 * Turns every exception on an API request into the standard JSON envelope.
 */
final class ApiExceptionRenderer
{
    public function __invoke(Throwable $e, Request $request): ?JsonResponse
    {
        if (! $request->is('api/*') && ! $request->expectsJson()) {
            return null;
        }

        return match (true) {
            $e instanceof ValidationException => response()->api(
                statusCode: 422,
                message: $e->validator->errors()->first(),
                errors: $e->errors(),
            ),
            $e instanceof ApiException => response()->api(
                statusCode: $e->statusCode(),
                message: $e->getMessage(),
            ),
            $e instanceof AuthenticationException,
            $e instanceof JWTException => response()->api(
                statusCode: 401,
                message: __('message.unauthenticated'),
            ),
            // Policy failures (AuthorizationException) arrive here as AccessDeniedHttpException.
            $e instanceof AccessDeniedHttpException => response()->api(
                statusCode: 403,
                message: __('message.you_dont_have_permission_to_access_this_resource'),
            ),
            // ModelNotFoundException arrives here as NotFoundHttpException.
            $e instanceof NotFoundHttpException => response()->api(
                statusCode: 404,
                message: __('message.not_found'),
            ),
            $e instanceof TooManyRequestsHttpException => response()->api(
                statusCode: 429,
                message: __('message.too_many_requests'),
            ),
            $e instanceof HttpExceptionInterface => response()->api(
                statusCode: $e->getStatusCode(),
                message: $e->getMessage(),
            ),
            // In debug mode, fall back to Laravel's detailed error output.
            default => config('app.debug') ? null : response()->api(
                statusCode: 500,
                message: __('message.server_error'),
            ),
        };
    }
}
