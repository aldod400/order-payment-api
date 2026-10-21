<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @return array<string, mixed>
     */
    private function orderPayload(array $overrides = []): array
    {
        return [
            'customer_name' => 'Ali Hassan',
            'customer_email' => 'ali@example.com',
            'customer_phone' => '+201000000000',
            'items' => [
                ['product_name' => 'Book', 'quantity' => 3, 'price' => 19.99],
                ['product_name' => 'Pen', 'quantity' => 1, 'price' => 0.10],
            ],
            ...$overrides,
        ];
    }

    public function test_a_user_can_create_an_order_and_the_total_is_calculated(): void
    {
        $response = $this->postJson('/api/v1/orders', $this->orderPayload(), $this->authHeaders($this->user));

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.total', '60.07')
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.items.0.subtotal', '59.97')
            ->assertJsonMissingPath('data.user_id');

        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id, 'total' => '60.07']);
        $this->assertDatabaseCount('order_items', 2);
    }

    public function test_a_total_sent_by_the_client_is_ignored(): void
    {
        $this->postJson('/api/v1/orders', $this->orderPayload(['total' => 1]), $this->authHeaders($this->user))
            ->assertCreated()
            ->assertJsonPath('data.total', '60.07');
    }

    public function test_create_validates_the_order_and_each_item(): void
    {
        $errors = $this->postJson('/api/v1/orders', [
            'customer_email' => 'not-an-email',
            'items' => [['product_name' => 'Book', 'quantity' => 0, 'price' => 19.999]],
        ], $this->authHeaders($this->user))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['customer_name', 'customer_email', 'items.0.quantity', 'items.0.price'], 'error')
            ->json('error');

        $this->assertSame('The quantity of item #1 field must be at least 1.', $errors['items.0.quantity'][0]);
    }

    public function test_an_order_needs_at_least_one_item(): void
    {
        $this->postJson('/api/v1/orders', $this->orderPayload(['items' => []]), $this->authHeaders($this->user))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['items'], 'error');
    }

    public function test_orders_need_authentication(): void
    {
        $this->getJson('/api/v1/orders')->assertUnauthorized();
        $this->postJson('/api/v1/orders', $this->orderPayload())->assertUnauthorized();
    }

    public function test_the_list_is_paginated_and_shows_only_my_orders(): void
    {
        Order::factory()->count(3)->for($this->user)->create();
        Order::factory()->count(2)->create();

        $this->getJson('/api/v1/orders?per_page=2', $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('total', 3)
            ->assertJsonPath('per_page', 2)
            ->assertJsonPath('last_page', 2)
            ->assertJsonStructure(['data', 'current_page', 'last_page', 'per_page', 'total', 'links' => ['first', 'last', 'prev', 'next']]);
    }

    public function test_the_list_can_be_filtered_by_status(): void
    {
        Order::factory()->for($this->user)->create();
        Order::factory()->count(2)->for($this->user)->confirmed()->create();
        Order::factory()->for($this->user)->cancelled()->create();

        $this->getJson('/api/v1/orders?status=confirmed', $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('total', 2)
            ->assertJsonPath('data.0.status', 'confirmed');
    }

    public function test_the_list_rejects_an_unknown_status_and_a_huge_page_size(): void
    {
        $this->getJson('/api/v1/orders?status=shipped&per_page=1000', $this->authHeaders($this->user))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status', 'per_page'], 'error');
    }

    public function test_a_user_can_view_their_order_with_items(): void
    {
        $order = Order::factory()->for($this->user)->create();
        OrderItem::factory()->count(2)->for($order)->create();

        $this->getJson("/api/v1/orders/{$order->id}", $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonCount(2, 'data.items');
    }

    public function test_a_user_cannot_view_someone_elses_order(): void
    {
        $order = Order::factory()->create();

        $this->getJson("/api/v1/orders/{$order->id}", $this->authHeaders($this->user))
            ->assertForbidden()
            ->assertJson(['status' => 403, 'message' => "You don't have permission to access this resource"]);
    }

    public function test_an_unknown_order_returns_404(): void
    {
        $this->getJson('/api/v1/orders/01a15fbc-0000-7000-8000-000000000000', $this->authHeaders($this->user))
            ->assertNotFound()
            ->assertJson(['status' => 404, 'message' => 'Resource not found']);

        $this->getJson('/api/v1/orders/not-a-uuid', $this->authHeaders($this->user))->assertNotFound();
    }

    public function test_a_pending_order_can_be_partly_updated(): void
    {
        $order = Order::factory()->for($this->user)->create(['customer_name' => 'Old Name']);

        $this->patchJson("/api/v1/orders/{$order->id}", ['customer_name' => 'New Name'], $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('data.customer_name', 'New Name')
            ->assertJsonPath('data.customer_email', $order->customer_email);
    }

    public function test_updating_items_replaces_them_and_recalculates_the_total(): void
    {
        $order = Order::factory()->for($this->user)->create();
        OrderItem::factory()->count(3)->for($order)->create();

        $this->patchJson("/api/v1/orders/{$order->id}", [
            'items' => [
                ['product_name' => 'Cup', 'quantity' => 2, 'price' => 0.10],
                ['product_name' => 'Plate', 'quantity' => 1, 'price' => 0.20],
            ],
        ], $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('data.total', '0.40')
            ->assertJsonCount(2, 'data.items');

        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseMissing('order_items', ['order_id' => $order->id, 'product_name' => 'Book']);
    }

    public function test_an_empty_update_is_rejected(): void
    {
        $order = Order::factory()->for($this->user)->create();

        $this->patchJson("/api/v1/orders/{$order->id}", [], $this->authHeaders($this->user))
            ->assertUnprocessable()
            ->assertJsonPath('error.order.0', 'Send at least one field to update');
    }

    public function test_a_pending_order_can_be_confirmed_or_cancelled(): void
    {
        $toConfirm = Order::factory()->for($this->user)->create();
        $toCancel = Order::factory()->for($this->user)->create();

        $this->patchJson("/api/v1/orders/{$toConfirm->id}", ['status' => 'confirmed'], $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('data.status', 'confirmed');

        $this->patchJson("/api/v1/orders/{$toCancel->id}", ['status' => 'cancelled'], $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');
    }

    public function test_a_confirmed_order_cannot_go_back_to_pending(): void
    {
        $order = Order::factory()->for($this->user)->confirmed()->create();

        $this->patchJson("/api/v1/orders/{$order->id}", ['status' => 'pending'], $this->authHeaders($this->user))
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Order status cannot change from confirmed to pending');
    }

    public function test_a_cancelled_order_cannot_change_status(): void
    {
        $order = Order::factory()->for($this->user)->cancelled()->create();

        $this->patchJson("/api/v1/orders/{$order->id}", ['status' => 'confirmed'], $this->authHeaders($this->user))
            ->assertUnprocessable();
    }

    public function test_a_paid_order_cannot_be_cancelled(): void
    {
        $order = Order::factory()->for($this->user)->confirmed()->create();
        Payment::factory()->for($order)->create();

        $this->patchJson("/api/v1/orders/{$order->id}", ['status' => 'cancelled'], $this->authHeaders($this->user))
            ->assertUnprocessable();

        $this->assertSame(OrderStatus::CONFIRMED, $order->fresh()->status);
    }

    public function test_only_pending_orders_can_have_details_changed(): void
    {
        $order = Order::factory()->for($this->user)->confirmed()->create();

        $this->patchJson("/api/v1/orders/{$order->id}", ['customer_name' => 'New Name'], $this->authHeaders($this->user))
            ->assertConflict()
            ->assertJsonPath('message', 'Only pending orders can have their details or items changed');
    }

    public function test_a_user_cannot_update_someone_elses_order(): void
    {
        $order = Order::factory()->create();

        $this->patchJson("/api/v1/orders/{$order->id}", ['status' => 'confirmed'], $this->authHeaders($this->user))
            ->assertForbidden();
    }

    public function test_an_order_without_payments_can_be_deleted_with_its_items(): void
    {
        $order = Order::factory()->for($this->user)->create();
        OrderItem::factory()->count(2)->for($order)->create();

        $this->deleteJson("/api/v1/orders/{$order->id}", [], $this->authHeaders($this->user))
            ->assertOk()
            ->assertJsonPath('message', 'Deleted successfully');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseCount('order_items', 0);
    }

    public function test_an_order_with_payments_cannot_be_deleted(): void
    {
        $order = Order::factory()->for($this->user)->confirmed()->create();
        Payment::factory()->for($order)->failed()->create();

        $this->deleteJson("/api/v1/orders/{$order->id}", [], $this->authHeaders($this->user))
            ->assertConflict()
            ->assertJsonPath('message', 'Order cannot be deleted because it has payments');

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_a_user_cannot_delete_someone_elses_order(): void
    {
        $order = Order::factory()->create();

        $this->deleteJson("/api/v1/orders/{$order->id}", [], $this->authHeaders($this->user))
            ->assertForbidden();

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_error_messages_can_be_in_arabic(): void
    {
        $order = Order::factory()->for($this->user)->confirmed()->create();
        Payment::factory()->for($order)->create();

        $this->deleteJson("/api/v1/orders/{$order->id}", [], [...$this->authHeaders($this->user), 'Accept-Language' => 'ar'])
            ->assertConflict()
            ->assertJsonPath('message', 'لا يمكن حذف الطلب لأنه يحتوي على مدفوعات');
    }
}
