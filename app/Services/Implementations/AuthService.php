<?php

namespace App\Services\Implementations;

use App\DTOs\Auth\AuthTokenDTO;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Container\Attributes\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepo,
        #[Auth('api')] private readonly JWTGuard $guard,
    ) {}

    public function register(RegisterDTO $dto): AuthTokenDTO
    {
        // The model's "hashed" cast hashes the password on save.
        /** @var User $user */
        $user = $this->userRepo->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        return $this->tokenFor($user, $this->guard->login($user));
    }

    public function login(LoginDTO $dto): AuthTokenDTO
    {
        $token = $this->guard->attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        if (! $token) {
            throw new InvalidCredentialsException;
        }

        $user = $this->guard->user();

        return $this->tokenFor($user, $token);
    }

    public function refresh(): AuthTokenDTO
    {
        $token = $this->guard->refresh();

        $user = $this->guard->setToken($token)->user();

        return $this->tokenFor($user, $token);
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    private function tokenFor(User $user, string $token): AuthTokenDTO
    {
        return new AuthTokenDTO(
            user: $user,
            token: $token,
            tokenType: 'bearer',
            expiresIn: JWTAuth::factory()->getTTL() * 60,
        );
    }
}
