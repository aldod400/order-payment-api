<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_gets_a_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Ali Hassan',
            'email' => 'ali@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('status', 201)
            ->assertJsonPath('data.user.email', 'ali@example.com')
            ->assertJsonPath('data.token_type', 'bearer')
            ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email'], 'token', 'expires_in']])
            ->assertJsonMissingPath('data.user.password');

        $user = User::firstWhere('email', 'ali@example.com');
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_register_validates_the_input(): void
    {
        $this->postJson('/api/v1/auth/register', [
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('status', 422)
            ->assertJsonValidationErrors(['name', 'email', 'password'], 'error');
    }

    public function test_register_rejects_a_duplicate_email(): void
    {
        User::factory()->create(['email' => 'ali@example.com']);

        $this->postJson('/api/v1/auth/register', [
            'name' => 'Ali',
            'email' => 'ali@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email'], 'error');
    }

    public function test_a_user_can_log_in(): void
    {
        User::factory()->create(['email' => 'ali@example.com', 'password' => 'secret123']);

        $this->postJson('/api/v1/auth/login', ['email' => 'ali@example.com', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonPath('data.user.email', 'ali@example.com')
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_login_with_a_wrong_password_returns_401(): void
    {
        User::factory()->create(['email' => 'ali@example.com', 'password' => 'secret123']);

        $this->postJson('/api/v1/auth/login', ['email' => 'ali@example.com', 'password' => 'wrong-pass1'])
            ->assertUnauthorized()
            ->assertJson(['status' => 401, 'message' => 'Invalid email or password']);
    }

    public function test_login_with_an_unknown_email_returns_the_same_401(): void
    {
        $this->postJson('/api/v1/auth/login', ['email' => 'nobody@example.com', 'password' => 'secret123'])
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Invalid email or password');
    }

    public function test_me_returns_the_logged_in_user(): void
    {
        $user = User::factory()->create();

        $this->getJson('/api/v1/auth/me', $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_protected_routes_need_a_token(): void
    {
        $this->getJson('/api/v1/auth/me')
            ->assertUnauthorized()
            ->assertJson(['status' => 401, 'message' => 'Unauthenticated, please login first']);
    }

    public function test_an_invalid_token_is_rejected(): void
    {
        $this->getJson('/api/v1/auth/me', ['Authorization' => 'Bearer not.a.real.token'])
            ->assertUnauthorized();
    }

    public function test_refresh_returns_a_new_token_and_blocks_the_old_one(): void
    {
        $user = User::factory()->create();
        $oldToken = $this->tokenFor($user);

        $newToken = $this->postJson('/api/v1/auth/refresh', [], ['Authorization' => "Bearer {$oldToken}"])
            ->assertOk()
            ->assertJsonPath('data.user.id', $user->id)
            ->json('data.token');

        $this->assertNotSame($oldToken, $newToken);

        $this->freshRequest()->getJson('/api/v1/auth/me', ['Authorization' => "Bearer {$oldToken}"])->assertUnauthorized();
        $this->freshRequest()->getJson('/api/v1/auth/me', ['Authorization' => "Bearer {$newToken}"])->assertOk();
    }

    public function test_logout_blocks_the_token(): void
    {
        $user = User::factory()->create();
        $token = $this->tokenFor($user);

        $this->postJson('/api/v1/auth/logout', [], ['Authorization' => "Bearer {$token}"])
            ->assertOk()
            ->assertJsonPath('message', 'Logged out successfully');

        $this->freshRequest()->getJson('/api/v1/auth/me', ['Authorization' => "Bearer {$token}"])->assertUnauthorized();
    }

    public function test_login_is_rate_limited(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->postJson('/api/v1/auth/login', ['email' => 'x@example.com', 'password' => 'wrong-pass1']);
        }

        $this->postJson('/api/v1/auth/login', ['email' => 'x@example.com', 'password' => 'wrong-pass1'])
            ->assertTooManyRequests()
            ->assertJsonPath('status', 429);
    }

    public function test_messages_follow_the_accept_language_header(): void
    {
        $this->postJson('/api/v1/auth/login', ['email' => 'x@example.com', 'password' => 'wrong-pass1'], ['Accept-Language' => 'ar'])
            ->assertUnauthorized()
            ->assertJsonPath('message', 'البريد الإلكتروني أو كلمة المرور غير صحيحة');
    }
}
