<?php

namespace App\Services\Contracts;

use App\DTOs\Auth\AuthTokenDTO;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Services\Implementations\AuthService;
use Illuminate\Container\Attributes\Bind;

#[Bind(AuthService::class)]
interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): AuthTokenDTO;

    public function login(LoginDTO $dto): AuthTokenDTO;

    public function refresh(): AuthTokenDTO;

    public function logout(): void;
}
