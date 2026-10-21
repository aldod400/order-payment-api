<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Auth\AuthTokenDTO;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Authentication
 *
 * Register, log in and manage your JWT. Send the token as `Authorization: Bearer {token}` on every other endpoint.
 * Login and register are limited to 5 requests per minute per IP.
 */
class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
    }

    /**
     * Register
     *
     * Create an account and receive a JWT straight away.
     *
     * @unauthenticated
     *
     * @bodyParam name string required Full name. Max 255 characters. Example: Ali Hassan
     * @bodyParam email string required A unique email address. Example: ali@example.com
     * @bodyParam password string required At least 8 characters, with letters and numbers. Example: secret123
     * @bodyParam password_confirmation string required Must match `password`. Example: secret123
     *
     * @response 201 scenario="Registered" {
     *   "status": 201,
     *   "message": "Registered successfully",
     *   "data": {
     *     "user": {"id": "9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12", "name": "Ali Hassan", "email": "ali@example.com", "created_at": "2026-10-21T18:32:42.000000Z"},
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5ZDhmMWU2MiJ9.signature",
     *     "token_type": "bearer",
     *     "expires_in": 3600
     *   }
     * }
     * @response 422 scenario="Validation error" {
     *   "status": 422,
     *   "message": "The email has already been taken.",
     *   "error": {
     *     "email": ["The email has already been taken."],
     *     "password": ["The password field must contain at least one number."]
     *   }
     * }
     * @response 429 scenario="Too many attempts" {"status": 429, "message": "Too many requests, please try again later"}
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $auth = $this->authService->register(RegisterDTO::fromRequest(
            name: $request->name,
            email: $request->email,
            password: $request->password,
        ));

        return response()->api(
            statusCode: 201,
            message: __('message.register_success'),
            data: $this->tokenData($auth),
        );
    }

    /**
     * Login
     *
     * Exchange email and password for a JWT.
     *
     * @unauthenticated
     *
     * @bodyParam email string required Example: ali@example.com
     * @bodyParam password string required Example: secret123
     *
     * @response 200 scenario="Logged in" {
     *   "status": 200,
     *   "message": "Logged in successfully",
     *   "data": {
     *     "user": {"id": "9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12", "name": "Ali Hassan", "email": "ali@example.com", "created_at": "2026-10-21T18:32:42.000000Z"},
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5ZDhmMWU2MiJ9.signature",
     *     "token_type": "bearer",
     *     "expires_in": 3600
     *   }
     * }
     * @response 401 scenario="Wrong email or password" {"status": 401, "message": "Invalid email or password"}
     * @response 422 scenario="Validation error" {"status": 422, "message": "The email field is required.", "error": {"email": ["The email field is required."]}}
     * @response 429 scenario="Too many attempts" {"status": 429, "message": "Too many requests, please try again later"}
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $auth = $this->authService->login(LoginDTO::fromRequest(
            email: $request->email,
            password: $request->password,
        ));

        return response()->api(
            message: __('message.login_success'),
            data: $this->tokenData($auth),
        );
    }

    /**
     * Current user
     *
     * Return the user who owns the token.
     *
     * @response 200 scenario="Success" {
     *   "status": 200,
     *   "message": "Success",
     *   "data": {"id": "9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12", "name": "Ali Hassan", "email": "ali@example.com", "created_at": "2026-10-21T18:32:42.000000Z"}
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     */
    public function me(): JsonResponse
    {
        return response()->api(
            message: __('message.success'),
            data: UserResource::make(auth('api')->user()),
        );
    }

    /**
     * Refresh token
     *
     * Exchange the current token (even an expired one, inside the refresh window) for a new one.
     * The old token stops working.
     *
     * @response 200 scenario="Refreshed" {
     *   "status": 200,
     *   "message": "Token refreshed successfully",
     *   "data": {
     *     "user": {"id": "9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12", "name": "Ali Hassan", "email": "ali@example.com", "created_at": "2026-10-21T18:32:42.000000Z"},
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJuZXcifQ.signature",
     *     "token_type": "bearer",
     *     "expires_in": 3600
     *   }
     * }
     * @response 401 scenario="Token missing, invalid or already used" {"status": 401, "message": "Unauthenticated, please login first"}
     */
    public function refresh(): JsonResponse
    {
        return response()->api(
            message: __('message.token_refreshed'),
            data: $this->tokenData($this->authService->refresh()),
        );
    }

    /**
     * Logout
     *
     * Invalidate the current token.
     *
     * @response 200 scenario="Logged out" {"status": 200, "message": "Logged out successfully"}
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->api(message: __('message.logout_success'));
    }

    /**
     * @return array<string, mixed>
     */
    private function tokenData(AuthTokenDTO $auth): array
    {
        return [
            'user' => UserResource::make($auth->user),
            'token' => $auth->token,
            'token_type' => $auth->tokenType,
            'expires_in' => $auth->expiresIn,
        ];
    }
}
