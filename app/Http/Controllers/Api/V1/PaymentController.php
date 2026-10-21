<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Payment\PaymentFilterDTO;
use App\DTOs\Payment\ProcessPaymentDTO;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ListPaymentsRequest;
use App\Http\Requests\Payment\ProcessPaymentRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @group Payments
 *
 * Pay for confirmed orders and view payments. Gateways are simulated:
 *
 * - `credit_card`: a card number ending in `0002` is declined; any other card is approved.
 * - `paypal`: an email ending in `@decline.test` is declined; any other email is approved.
 *
 * Every payment request needs an `Idempotency-Key` header. Retrying with the same key returns the
 * original payment (200) and never charges twice.
 */
class PaymentController extends Controller
{
    public function __construct(private readonly PaymentServiceInterface $paymentService) {}

    /**
     * List payments
     *
     * Payments across all your orders, newest first, paginated.
     *
     * @queryParam page integer Page number. Defaults to 1. Example: 1
     * @queryParam per_page integer Items per page, 1 to 100. Defaults to 15. Example: 15
     *
     * @response 200 scenario="Success" {
     *   "status": 200,
     *   "message": "Success",
     *   "data": [
     *     {
     *       "id": "01a15fbc-b0c1-73f3-8faf-84d0b62179b8",
     *       "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *       "payment_method": "credit_card",
     *       "status": "successful",
     *       "amount": "59.97",
     *       "transaction_id": "cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13",
     *       "gateway_response": {"gateway": "credit_card", "card_last4": "1111", "amount": "59.97", "currency": "USD", "message": "Approved"},
     *       "created_at": "2026-10-21T18:40:05.000000Z",
     *       "updated_at": "2026-10-21T18:40:05.000000Z"
     *     }
     *   ],
     *   "current_page": 1,
     *   "from": 1,
     *   "last_page": 1,
     *   "per_page": 15,
     *   "to": 1,
     *   "total": 1,
     *   "links": {
     *     "first": "http://localhost:8000/api/v1/payments?page=1",
     *     "last": "http://localhost:8000/api/v1/payments?page=1",
     *     "prev": null,
     *     "next": null
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     */
    public function index(ListPaymentsRequest $request): JsonResponse
    {
        return $this->paginated($request, orderId: null);
    }

    /**
     * List an order's payments
     *
     * All payment attempts for one of your orders, including failed ones.
     *
     * @urlParam order_id string required The order ID (UUID). Example: 01a15fbc-af62-704f-8643-f69b490d1ea9
     *
     * @queryParam page integer Page number. Defaults to 1. Example: 1
     * @queryParam per_page integer Items per page, 1 to 100. Defaults to 15. Example: 15
     *
     * @response 200 scenario="Success" {
     *   "status": 200,
     *   "message": "Success",
     *   "data": [
     *     {
     *       "id": "01a15fbc-b0c1-73f3-8faf-84d0b62179b8",
     *       "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *       "payment_method": "credit_card",
     *       "status": "successful",
     *       "amount": "59.97",
     *       "transaction_id": "cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13",
     *       "gateway_response": {"gateway": "credit_card", "card_last4": "1111", "amount": "59.97", "currency": "USD", "message": "Approved"},
     *       "created_at": "2026-10-21T18:40:05.000000Z",
     *       "updated_at": "2026-10-21T18:40:05.000000Z"
     *     },
     *     {
     *       "id": "01a15fbc-b08a-72c6-97db-9031a7da2a08",
     *       "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *       "payment_method": "credit_card",
     *       "status": "failed",
     *       "amount": "59.97",
     *       "transaction_id": null,
     *       "gateway_response": {"gateway": "credit_card", "card_last4": "0002", "amount": "59.97", "currency": "USD", "message": "Card declined"},
     *       "created_at": "2026-10-21T18:39:51.000000Z",
     *       "updated_at": "2026-10-21T18:39:51.000000Z"
     *     }
     *   ],
     *   "current_page": 1,
     *   "from": 1,
     *   "last_page": 1,
     *   "per_page": 15,
     *   "to": 2,
     *   "total": 2,
     *   "links": {
     *     "first": "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments?page=1",
     *     "last": "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments?page=1",
     *     "prev": null,
     *     "next": null
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 403 scenario="Someone else's order" {"status": 403, "message": "You don't have permission to access this resource"}
     * @response 404 scenario="Order not found" {"status": 404, "message": "Resource not found"}
     */
    public function orderPayments(ListPaymentsRequest $request, Order $order): JsonResponse
    {
        Gate::authorize('view', $order);

        return $this->paginated($request, orderId: $order->id);
    }

    /**
     * Pay for an order
     *
     * Charges a `confirmed` order through the chosen gateway for the order's full total.
     * A declined payment is still saved (status `failed`, HTTP 201), and the order can be paid again with a new key.
     *
     * The fields inside `details` depend on `payment_method`:
     * - `credit_card`: `card_number`, `expiry_month`, `expiry_year`, `cvv`, `holder_name`
     * - `paypal`: `paypal_email`
     *
     * @urlParam order_id string required The order ID (UUID). Example: 01a15fbc-af62-704f-8643-f69b490d1ea9
     *
     * @header Idempotency-Key 7f9c2e4a-1b3d-4e5f-8a6b-9c0d1e2f3a4b
     *
     * @bodyParam payment_method string required One of the configured gateways: `credit_card` or `paypal`. Example: credit_card
     * @bodyParam details object required Gateway-specific fields.
     * @bodyParam details.card_number string 13 to 19 digits. Required for `credit_card`. Example: 4111111111111111
     * @bodyParam details.expiry_month integer 1 to 12. Required for `credit_card`. Example: 12
     * @bodyParam details.expiry_year integer Not in the past. Required for `credit_card`. Example: 2030
     * @bodyParam details.cvv string 3 or 4 digits. Required for `credit_card`. Example: 123
     * @bodyParam details.holder_name string Required for `credit_card`. Example: Ali Hassan
     * @bodyParam details.paypal_email string Required for `paypal`. Example: No-example
     *
     * @response 201 scenario="Paid" {
     *   "status": 201,
     *   "message": "Payment completed successfully",
     *   "data": {
     *     "id": "01a15fbc-b0c1-73f3-8faf-84d0b62179b8",
     *     "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "payment_method": "credit_card",
     *     "status": "successful",
     *     "amount": "59.97",
     *     "transaction_id": "cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13",
     *     "gateway_response": {"gateway": "credit_card", "card_last4": "1111", "amount": "59.97", "currency": "USD", "message": "Approved"},
     *     "created_at": "2026-10-21T18:40:05.000000Z",
     *     "updated_at": "2026-10-21T18:40:05.000000Z"
     *   }
     * }
     * @response 201 scenario="Declined" {
     *   "status": 201,
     *   "message": "Payment was declined",
     *   "data": {
     *     "id": "01a15fbc-b08a-72c6-97db-9031a7da2a08",
     *     "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "payment_method": "credit_card",
     *     "status": "failed",
     *     "amount": "59.97",
     *     "transaction_id": null,
     *     "gateway_response": {"gateway": "credit_card", "card_last4": "0002", "amount": "59.97", "currency": "USD", "message": "Card declined"},
     *     "created_at": "2026-10-21T18:39:51.000000Z",
     *     "updated_at": "2026-10-21T18:39:51.000000Z"
     *   }
     * }
     * @response 200 scenario="Retry with the same Idempotency-Key" {
     *   "status": 200,
     *   "message": "Payment completed successfully",
     *   "data": {
     *     "id": "01a15fbc-b0c1-73f3-8faf-84d0b62179b8",
     *     "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "payment_method": "credit_card",
     *     "status": "successful",
     *     "amount": "59.97",
     *     "transaction_id": "cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13",
     *     "gateway_response": {"gateway": "credit_card", "card_last4": "1111", "amount": "59.97", "currency": "USD", "message": "Approved"},
     *     "created_at": "2026-10-21T18:40:05.000000Z",
     *     "updated_at": "2026-10-21T18:40:05.000000Z"
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 403 scenario="Someone else's order" {"status": 403, "message": "You don't have permission to access this resource"}
     * @response 404 scenario="Order not found" {"status": 404, "message": "Resource not found"}
     * @response 409 scenario="Already paid" {"status": 409, "message": "This order has already been paid"}
     * @response 422 scenario="Order not confirmed" {"status": 422, "message": "Payments can only be processed for confirmed orders"}
     * @response 422 scenario="Validation error" {
     *   "status": 422,
     *   "message": "The idempotency key field is required.",
     *   "error": {
     *     "idempotency_key": ["The idempotency key field is required."],
     *     "details.cvv": ["The details.cvv field is required."]
     *   }
     * }
     * @response 422 scenario="Unknown payment method" {"status": 422, "message": "The selected payment method is invalid.", "error": {"payment_method": ["The selected payment method is invalid."]}}
     */
    public function store(ProcessPaymentRequest $request, Order $order): JsonResponse
    {
        Gate::authorize('pay', $order);

        $payment = $this->paymentService->process(ProcessPaymentDTO::fromRequest(
            orderId: $order->id,
            paymentMethod: $request->payment_method,
            idempotencyKey: $request->idempotency_key,
            details: $request->details,
        ));

        return response()->api(
            statusCode: $payment->wasRecentlyCreated ? 201 : 200,
            message: $this->outcomeMessage($payment),
            data: BaseResource::make(PaymentResource::make($payment)),
        );
    }

    /**
     * Show a payment
     *
     * One payment that belongs to one of your orders.
     *
     * @urlParam id string required The payment ID (UUID). Example: 01a15fbc-b0c1-73f3-8faf-84d0b62179b8
     *
     * @response 200 scenario="Success" {
     *   "status": 200,
     *   "message": "Success",
     *   "data": {
     *     "id": "01a15fbc-b0c1-73f3-8faf-84d0b62179b8",
     *     "order_id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "payment_method": "paypal",
     *     "status": "successful",
     *     "amount": "59.97",
     *     "transaction_id": "pp_a7619984-e5b5-4ad7-8ab7-00134378d34c",
     *     "gateway_response": {"gateway": "paypal", "payer_email": "buyer@example.com", "amount": "59.97", "currency": "USD", "message": "Approved"},
     *     "created_at": "2026-10-21T18:40:05.000000Z",
     *     "updated_at": "2026-10-21T18:40:05.000000Z"
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 403 scenario="Someone else's payment" {"status": 403, "message": "You don't have permission to access this resource"}
     * @response 404 scenario="Payment not found" {"status": 404, "message": "Resource not found"}
     */
    public function show(Payment $payment): JsonResponse
    {
        Gate::authorize('view', $payment);

        return response()->api(
            message: __('message.success'),
            data: BaseResource::make(PaymentResource::make($payment)),
        );
    }

    private function paginated(ListPaymentsRequest $request, ?string $orderId): JsonResponse
    {
        $payments = $this->paymentService->list(PaymentFilterDTO::fromRequest(
            userId: auth('api')->id(),
            orderId: $orderId,
            perPage: $request->integer('per_page', 15),
        ));

        return response()->api(
            message: __('message.success'),
            data: BaseResource::make(PaymentResource::collection($payments)),
        );
    }

    private function outcomeMessage(Payment $payment): string
    {
        return match ($payment->status) {
            PaymentStatus::SUCCESSFUL => __('message.payment_successful'),
            PaymentStatus::FAILED => __('message.payment_failed'),
            PaymentStatus::PENDING => __('message.payment_pending'),
        };
    }
}
