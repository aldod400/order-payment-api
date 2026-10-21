<?php

namespace App\Services\Implementations;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\OrderItemDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Enums\OrderStatus;
use App\Exceptions\Order\InvalidOrderStatusTransitionException;
use App\Exceptions\Order\OrderHasPaymentsException;
use App\Exceptions\Order\OrderNotEditableException;
use App\Models\Order;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    /**
     * Allowed status changes: current status => statuses it may move to.
     */
    private const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['cancelled'],
        'cancelled' => [],
    ];

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly OrderItemRepositoryInterface $orderItemRepo,
        private readonly PaymentRepositoryInterface $paymentRepo,
    ) {
    }

    public function list(OrderFilterDTO $filter): LengthAwarePaginator
    {
        return $this->orderRepo->paginateForUser($filter);
    }

    public function create(CreateOrderDTO $dto): Order
    {
        $rows = $this->buildItemRows($dto->items);

        return DB::transaction(function () use ($dto, $rows): Order {
            /** @var Order $order */
            $order = $this->orderRepo->create([
                'user_id' => $dto->userId,
                'customer_name' => $dto->customerName,
                'customer_email' => $dto->customerEmail,
                'customer_phone' => $dto->customerPhone,
                'status' => OrderStatus::PENDING,
                'total' => $this->sumSubtotals($rows),
            ]);

            $this->orderItemRepo->insertMany($this->withOrderId($rows, $order->id));

            return $order->load('items');
        });
    }

    public function update(Order $order, UpdateOrderDTO $dto): Order
    {
        return DB::transaction(function () use ($order, $dto): Order {
            // Lock the row so a payment or another update can't run at the same time.
            $order = $this->orderRepo->findForUpdate($order->id);

            $changesDetails = $dto->customerName !== null
                || $dto->customerEmail !== null
                || $dto->customerPhone !== null
                || $dto->items !== null;

            if ($changesDetails && $order->status !== OrderStatus::PENDING) {
                throw new OrderNotEditableException();
            }

            $data = array_filter([
                'customer_name' => $dto->customerName,
                'customer_email' => $dto->customerEmail,
                'customer_phone' => $dto->customerPhone,
            ], fn ($value) => $value !== null);

            if ($dto->status !== null && $dto->status !== $order->status) {
                $this->ensureCanTransition($order, $dto->status);
                $data['status'] = $dto->status;
            }

            if ($dto->items !== null) {
                $rows = $this->buildItemRows($dto->items);

                $this->orderItemRepo->deleteByOrder($order->id);
                $this->orderItemRepo->insertMany($this->withOrderId($rows, $order->id));

                $data['total'] = $this->sumSubtotals($rows);
            }

            /** @var Order $order */
            $order = $this->orderRepo->update($order, $data);

            return $order->load('items');
        });
    }

    public function delete(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            $order = $this->orderRepo->findForUpdate($order->id);

            if ($this->paymentRepo->existsForOrder($order->id)) {
                throw new OrderHasPaymentsException();
            }

            $this->orderRepo->delete($order);
        });
    }

    private function ensureCanTransition(Order $order, OrderStatus $to): void
    {
        $allowed = in_array($to->value, self::TRANSITIONS[$order->status->value], true);

        // A paid order can no longer be cancelled.
        if ($allowed && $to === OrderStatus::CANCELLED && $this->paymentRepo->hasSuccessfulPayment($order->id)) {
            $allowed = false;
        }

        if (! $allowed) {
            throw new InvalidOrderStatusTransitionException($order->status, $to);
        }
    }

    /**
     * @param  list<OrderItemDTO>  $items
     * @return list<array{product_name: string, quantity: int, price: string, subtotal: string}>
     */
    private function buildItemRows(array $items): array
    {
        return array_map(fn (OrderItemDTO $item) => [
            'product_name' => $item->productName,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'subtotal' => bcmul((string) $item->quantity, $item->price, 2),
        ], $items);
    }

    /**
     * @param  list<array{subtotal: string}>  $rows
     */
    private function sumSubtotals(array $rows): string
    {
        return array_reduce($rows, fn (string $total, array $row) => bcadd($total, $row['subtotal'], 2), '0.00');
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private function withOrderId(array $rows, string $orderId): array
    {
        return array_map(fn (array $row) => [...$row, 'order_id' => $orderId], $rows);
    }
}
