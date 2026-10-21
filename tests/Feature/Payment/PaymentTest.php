<?php

namespace Tests\Feature\Payment;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Fakes\FakeStripeGateway;
use Tests\Fakes\ThrowingGateway;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->order = Order::factory()->for($this->user)->confirmed()->create(['total' => '60.07']);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    private function pay(array $body, ?string $key = 'key-1', ?Order $order = null)
    {
        $headers = $this->authHeaders($this->user);

        if ($key !== null) {
            $headers['Idempotency-Key'] = $key;
        }

        return $this->postJson('/api/v1/orders/'.($order ?? $this->order)->id.'/payments', $body, $headers);
    }

    private function payByCard(string $cardNumber = '4111111111111111', ?string $key = 'key-1')
    {
        return $this->pay(['payment_method' => 'credit_card', 'details' => $this->cardDetails($cardNumber)], $key);
    }

    public function test_a_confirmed_order_can_be_paid_by_card(): void
    {
        $this->payByCard()
            ->assertCreated()
            ->assertJsonPath('message', 'Payment completed successfully')
            ->assertJsonPath('data.status', 'successful')
            ->assertJsonPath('data.payment_method', 'credit_card')
            ->assertJsonPath('data.amount', '60.07')
            ->assertJsonPath('data.order_id', $this->order->id)
            ->assertJsonPath('data.gateway_response.card_last4', '1111')
            ->assertJsonMissingPath('data.idempotency_key');

        $this->assertDatabaseHas('payments', ['order_id' => $this->order->id, 'status' => 'successful']);
    }

    public function test_a_confirmed_order_can_be_paid_by_paypal(): void
    {
        $this->pay(['payment_method' => 'paypal', 'details' => ['paypal_email' => 'buyer@example.com']])
            ->assertCreated()
            ->assertJsonPath('data.status', 'successful')
            ->assertJsonPath('data.payment_method', 'paypal');
    }

    public function test_only_confirmed_orders_can_be_paid(): void
    {
        foreach ([Order::factory()->for($this->user)->create(), Order::factory()->for($this->user)->cancelled()->create()] as $order) {
            $this->pay(['payment_method' => 'paypal', 'details' => ['paypal_email' => 'a@example.com']], 'key-x', $order)
                ->assertUnprocessable()
                ->assertJsonPath('message', 'Payments can only be processed for confirmed orders');
        }

        $this->assertDatabaseCount('payments', 0);
    }

    public function test_a_declined_card_is_saved_as_failed(): void
    {
        $this->payByCard('4000000000000002')
            ->assertCreated()
            ->assertJsonPath('message', 'Payment was declined')
            ->assertJsonPath('data.status', 'failed')
            ->assertJsonPath('data.transaction_id', null);
    }

    public function test_a_failed_payment_can_be_retried_with_another_method(): void
    {
        $this->payByCard('4000000000000002', 'key-1')->assertJsonPath('data.status', 'failed');

        $this->pay(['payment_method' => 'paypal', 'details' => ['paypal_email' => 'a@example.com']], 'key-2')
            ->assertCreated()
            ->assertJsonPath('data.status', 'successful');

        $this->assertDatabaseCount('payments', 2);
    }

    public function test_the_same_idempotency_key_does_not_charge_twice(): void
    {
        $first = $this->payByCard(key: 'same-key')->assertCreated()->json('data');

        $this->payByCard(key: 'same-key')
            ->assertOk()
            ->assertJsonPath('data.id', $first['id'])
            ->assertJsonPath('data.transaction_id', $first['transaction_id']);

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_a_paid_order_cannot_be_paid_again_with_a_new_key(): void
    {
        $this->payByCard(key: 'key-1')->assertCreated();

        $this->payByCard(key: 'key-2')
            ->assertConflict()
            ->assertJsonPath('message', 'This order has already been paid');

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_a_payment_still_in_progress_blocks_a_second_payment(): void
    {
        Payment::factory()->for($this->order)->pending()->create();

        $this->payByCard(key: 'key-new')->assertConflict();
    }

    public function test_the_idempotency_key_header_is_required(): void
    {
        $this->payByCard(key: null)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['idempotency_key'], 'error');
    }

    public function test_an_unknown_payment_method_is_rejected(): void
    {
        $this->pay(['payment_method' => 'bitcoin', 'details' => ['wallet' => 'x']])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['payment_method'], 'error');
    }

    public function test_each_gateway_validates_its_own_details(): void
    {
        $this->pay(['payment_method' => 'credit_card', 'details' => ['card_number' => '12']])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'details.card_number', 'details.expiry_month', 'details.expiry_year', 'details.cvv', 'details.holder_name',
            ], 'error');

        $this->pay(['payment_method' => 'paypal', 'details' => ['paypal_email' => 'nope']])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['details.paypal_email'], 'error');
    }

    public function test_the_full_card_number_is_never_stored(): void
    {
        $this->payByCard('4111111111111111')->assertCreated();

        $this->assertStringNotContainsString('4111111111111111', json_encode(Payment::first()->getAttributes()));
    }

    public function test_a_gateway_error_marks_the_payment_failed(): void
    {
        config(['payments.gateways.broken' => ['class' => ThrowingGateway::class]]);

        $this->pay(['payment_method' => 'broken', 'details' => ['x' => 'y']])
            ->assertCreated()
            ->assertJsonPath('data.status', 'failed')
            ->assertJsonPath('data.gateway_response.message', 'Gateway error');

        $this->assertSame(PaymentStatus::FAILED, Payment::first()->status);
    }

    public function test_a_new_gateway_works_end_to_end_with_only_a_config_entry(): void
    {
        config(['payments.gateways.stripe' => ['class' => FakeStripeGateway::class, 'key' => 'sk_test_1']]);

        $this->pay(['payment_method' => 'stripe', 'details' => []])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['details.token'], 'error');

        $this->pay(['payment_method' => 'stripe', 'details' => ['token' => 'tok_visa']])
            ->assertCreated()
            ->assertJsonPath('data.status', 'successful')
            ->assertJsonPath('data.transaction_id', 'st_'.$this->order->id);
    }

    public function test_a_user_cannot_pay_someone_elses_order(): void
    {
        $order = Order::factory()->confirmed()->create();

        $this->pay(['payment_method' => 'paypal', 'details' => ['paypal_email' => 'a@example.com']], 'key-1', $order)
            ->assertForbidden();

        $this->assertDatabaseCount('payments', 0);
    }

    public function test_payments_need_authentication(): void
    {
        $this->postJson("/api/v1/orders/{$this->order->id}/payments", [])->assertUnauthorized();
        $this->getJson('/api/v1/payments')->assertUnauthorized();
    }

    public function test_a_user_sees_only_their_own_payments(): void
    {
        Payment::factory()->count(2)->for($this->order)->failed()->create();
        Payment::factory()->count(3)->create();

        $this->getJson('/api/v1/payments', $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('total', 2)
            ->assertJsonStructure(['data', 'current_page', 'last_page', 'per_page', 'total', 'links']);
    }

    public function test_payments_of_one_order_can_be_listed(): void
    {
        $otherOrder = Order::factory()->for($this->user)->confirmed()->create();
        Payment::factory()->count(2)->for($this->order)->failed()->create();
        Payment::factory()->for($otherOrder)->create();

        $this->getJson("/api/v1/orders/{$this->order->id}/payments", $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('total', 2)
            ->assertJsonPath('data.0.order_id', $this->order->id);
    }

    public function test_a_user_cannot_list_payments_of_someone_elses_order(): void
    {
        $order = Order::factory()->confirmed()->create();

        $this->getJson("/api/v1/orders/{$order->id}/payments", $this->authHeaders($this->user))
            ->assertForbidden();
    }

    public function test_a_user_can_view_their_payment_but_not_someone_elses(): void
    {
        $mine = Payment::factory()->for($this->order)->create();
        $theirs = Payment::factory()->create();

        $this->getJson("/api/v1/payments/{$mine->id}", $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('data.id', $mine->id);

        $this->getJson("/api/v1/payments/{$theirs->id}", $this->authHeaders($this->user))
            ->assertForbidden();
    }
}
