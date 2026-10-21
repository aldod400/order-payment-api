<?php

namespace App\Services\Implementations;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentFilterDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\DTOs\Payment\ProcessPaymentDTO;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\Payment\OrderAlreadyPaidException;
use App\Exceptions\Payment\OrderNotConfirmedException;
use App\Models\Payment;
use App\Payments\Contracts\PaymentGatewayInterface;
use App\Payments\Contracts\PaymentGatewayManagerInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly PaymentRepositoryInterface $paymentRepo,
        private readonly PaymentGatewayManagerInterface $gateways,
    ) {
    }

    public function list(PaymentFilterDTO $filter): LengthAwarePaginator
    {
        return $this->paymentRepo->paginateFiltered($filter);
    }

    public function process(ProcessPaymentDTO $dto): Payment
    {
        $gateway = $this->gateways->resolve($dto->paymentMethod);

        $payment = DB::transaction(function () use ($dto): Payment {
            $order = $this->orderRepo->findForUpdate($dto->orderId);

            $existing = $this->paymentRepo->findByIdempotencyKey($order->id, $dto->idempotencyKey);

            if ($existing) {
                return $existing;
            }

            if ($order->status !== OrderStatus::CONFIRMED) {
                throw new OrderNotConfirmedException();
            }

            if ($this->paymentRepo->hasActivePayment($order->id)) {
                throw new OrderAlreadyPaidException();
            }

            return $this->paymentRepo->create([
                'order_id' => $order->id,
                'payment_method' => $dto->paymentMethod,
                'status' => PaymentStatus::PENDING,
                'amount' => $order->total,
                'idempotency_key' => $dto->idempotencyKey,
            ]);
        });

        if (! $payment->wasRecentlyCreated) {
            return $payment;
        }

        $result = $this->charge($gateway, $payment, $dto);

        return $this->paymentRepo->update($payment, [
            'status' => $result->status,
            'transaction_id' => $result->transactionId,
            'gateway_response' => $result->response,
        ]);
    }

    private function charge(PaymentGatewayInterface $gateway, Payment $payment, ProcessPaymentDTO $dto): PaymentResultDTO
    {
        try {
            return $gateway->charge(new ChargeDTO(
                orderId: $payment->order_id,
                amount: (string) $payment->amount,
                currency: config('payments.currency'),
                idempotencyKey: $payment->idempotency_key,
                details: $dto->details,
            ));
        } catch (Throwable $e) {
            // Never leave a payment stuck in "pending" when the gateway errors.
            report($e);

            return PaymentResultDTO::failed(['message' => 'Gateway error']);
        }
    }
}
