<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory()->confirmed(),
            'payment_method' => 'credit_card',
            'status' => PaymentStatus::SUCCESSFUL,
            'amount' => fake()->randomFloat(2, 10, 1000),
            'transaction_id' => 'txn_'.fake()->unique()->uuid(),
            'gateway_response' => ['message' => 'Approved'],
            'idempotency_key' => fake()->uuid(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => PaymentStatus::PENDING]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => PaymentStatus::FAILED,
            'transaction_id' => null,
            'gateway_response' => ['message' => 'Card declined'],
        ]);
    }
}
