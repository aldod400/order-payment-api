<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    /**
     * Issue a JWT for the user and return the auth header.
     *
     * @return array<string, string>
     */
    protected function authHeaders(User $user): array
    {
        return ['Authorization' => 'Bearer '.$this->tokenFor($user)];
    }

    protected function tokenFor(User $user): string
    {
        return JWTAuth::fromUser($user);
    }

    /**
     * Forget the logged-in user between requests, like a new HTTP request would.
     */
    protected function freshRequest(): static
    {
        $this->app['auth']->forgetGuards();
        $this->app['tymon.jwt']->unsetToken();
        JWTAuth::unsetToken();

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function cardDetails(string $cardNumber = '4111111111111111'): array
    {
        return [
            'card_number' => $cardNumber,
            'expiry_month' => 12,
            'expiry_year' => now()->addYear()->year,
            'cvv' => '123',
            'holder_name' => 'Ali Hassan',
        ];
    }
}
