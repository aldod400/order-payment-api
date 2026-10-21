<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\OrderItemDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ListOrdersRequest;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @group Orders
 *
 * Create and manage your orders. You only ever see and change your own orders.
 *
 * Status flow: `pending` → `confirmed` or `cancelled`; `confirmed` → `cancelled` (only while unpaid).
 * Customer details and items can only change while the order is `pending`.
 */
class OrderController extends Controller
{
    public function __construct(private readonly OrderServiceInterface $orderService) {}

    /**
     * List orders
     *
     * Your orders, newest first, paginated.
     *
     * @queryParam status string Filter by status: `pending`, `confirmed` or `cancelled`. Example: pending
     * @queryParam page integer Page number. Defaults to 1. Example: 1
     * @queryParam per_page integer Items per page, 1 to 100. Defaults to 15. Example: 15
     *
     * @response 200 scenario="Success" {
     *   "status": 200,
     *   "message": "Success",
     *   "data": [
     *     {
     *       "id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *       "customer_name": "Ali Hassan",
     *       "customer_email": "ali@example.com",
     *       "customer_phone": "+201001234567",
     *       "status": "pending",
     *       "total": "60.07",
     *       "items": [
     *         {"id": "01a15fbc-af64-7190-8a65-6e8f95840565", "product_name": "Book", "quantity": 3, "price": "19.99", "subtotal": "59.97"},
     *         {"id": "01a15fbc-af64-7190-8a65-6e8f95e6e532", "product_name": "Pen", "quantity": 1, "price": "0.10", "subtotal": "0.10"}
     *       ],
     *       "created_at": "2026-10-21T18:32:42.000000Z",
     *       "updated_at": "2026-10-21T18:32:42.000000Z"
     *     }
     *   ],
     *   "current_page": 1,
     *   "from": 1,
     *   "last_page": 1,
     *   "per_page": 15,
     *   "to": 1,
     *   "total": 1,
     *   "links": {
     *     "first": "http://localhost:8000/api/v1/orders?page=1",
     *     "last": "http://localhost:8000/api/v1/orders?page=1",
     *     "prev": null,
     *     "next": null
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 422 scenario="Invalid filter" {"status": 422, "message": "The selected status is invalid.", "error": {"status": ["The selected status is invalid."]}}
     */
    public function index(ListOrdersRequest $request): JsonResponse
    {
        $orders = $this->orderService->list(OrderFilterDTO::fromRequest(
            userId: auth('api')->id(),
            status: $request->enum('status', OrderStatus::class),
            perPage: $request->integer('per_page', 15),
        ));

        return response()->api(
            message: __('message.success'),
            data: BaseResource::make(OrderResource::collection($orders)),
        );
    }

    /**
     * Create an order
     *
     * The order starts as `pending`. Each item's subtotal and the order total are calculated on the server;
     * a `total` sent by the client is ignored.
     *
     * @bodyParam customer_name string required Max 255 characters. Example: Ali Hassan
     * @bodyParam customer_email string required Example: ali@example.com
     * @bodyParam customer_phone string Optional. Max 30 characters. Example: +201001234567
     * @bodyParam items object[] required 1 to 100 items.
     * @bodyParam items[].product_name string required Example: Book
     * @bodyParam items[].quantity integer required 1 to 10000. Example: 3
     * @bodyParam items[].price number required Unit price, up to 2 decimal places. Example: 19.99
     *
     * @response 201 scenario="Created" {
     *   "status": 201,
     *   "message": "Created successfully",
     *   "data": {
     *     "id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "customer_name": "Ali Hassan",
     *     "customer_email": "ali@example.com",
     *     "customer_phone": "+201001234567",
     *     "status": "pending",
     *     "total": "59.97",
     *     "items": [
     *       {"id": "01a15fbc-af64-7190-8a65-6e8f95840565", "product_name": "Book", "quantity": 3, "price": "19.99", "subtotal": "59.97"}
     *     ],
     *     "created_at": "2026-10-21T18:32:42.000000Z",
     *     "updated_at": "2026-10-21T18:32:42.000000Z"
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 422 scenario="Validation error" {
     *   "status": 422,
     *   "message": "The customer name field is required.",
     *   "error": {
     *     "customer_name": ["The customer name field is required."],
     *     "items.0.quantity": ["The quantity of item #1 field must be at least 1."],
     *     "items.0.price": ["The price of item #1 field must have 0-2 decimal places."]
     *   }
     * }
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create(CreateOrderDTO::fromRequest(
            userId: auth('api')->id(),
            customerName: $request->customer_name,
            customerEmail: $request->customer_email,
            customerPhone: $request->customer_phone,
            items: OrderItemDTO::collectionFromRequest($request->items),
        ));

        return response()->api(
            statusCode: 201,
            message: __('message.created_successfully'),
            data: BaseResource::make(OrderResource::make($order)),
        );
    }

    /**
     * Show an order
     *
     * One of your orders, with its items.
     *
     * @urlParam id string required The order ID (UUID). Example: 01a15fbc-af62-704f-8643-f69b490d1ea9
     *
     * @response 200 scenario="Success" {
     *   "status": 200,
     *   "message": "Success",
     *   "data": {
     *     "id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "customer_name": "Ali Hassan",
     *     "customer_email": "ali@example.com",
     *     "customer_phone": "+201001234567",
     *     "status": "confirmed",
     *     "total": "59.97",
     *     "items": [
     *       {"id": "01a15fbc-af64-7190-8a65-6e8f95840565", "product_name": "Book", "quantity": 3, "price": "19.99", "subtotal": "59.97"}
     *     ],
     *     "created_at": "2026-10-21T18:32:42.000000Z",
     *     "updated_at": "2026-10-21T18:35:10.000000Z"
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 403 scenario="Someone else's order" {"status": 403, "message": "You don't have permission to access this resource"}
     * @response 404 scenario="Order not found" {"status": 404, "message": "Resource not found"}
     */
    public function show(Order $order): JsonResponse
    {
        Gate::authorize('view', $order);

        return response()->api(
            message: __('message.success'),
            data: BaseResource::make(OrderResource::make($order->load('items'))),
        );
    }

    /**
     * Update an order
     *
     * Send only the fields you want to change (at least one).
     * Sending `items` replaces all current items and recalculates the total.
     * Use `status` to confirm or cancel the order.
     *
     * @urlParam id string required The order ID (UUID). Example: 01a15fbc-af62-704f-8643-f69b490d1ea9
     *
     * @bodyParam customer_name string Max 255 characters. Example: No-example
     * @bodyParam customer_email string Example: No-example
     * @bodyParam customer_phone string Max 30 characters. Example: No-example
     * @bodyParam status string `pending`, `confirmed` or `cancelled`. Example: confirmed
     * @bodyParam items object[] 1 to 100 items, each with `product_name`, `quantity` and `price` (same rules as Create). Replaces the current items. Example: No-example
     *
     * @response 200 scenario="Updated" {
     *   "status": 200,
     *   "message": "Updated successfully",
     *   "data": {
     *     "id": "01a15fbc-af62-704f-8643-f69b490d1ea9",
     *     "customer_name": "Ali Hassan",
     *     "customer_email": "ali@example.com",
     *     "customer_phone": "+201001234567",
     *     "status": "confirmed",
     *     "total": "59.97",
     *     "items": [
     *       {"id": "01a15fbc-af64-7190-8a65-6e8f95840565", "product_name": "Book", "quantity": 3, "price": "19.99", "subtotal": "59.97"}
     *     ],
     *     "created_at": "2026-10-21T18:32:42.000000Z",
     *     "updated_at": "2026-10-21T18:35:10.000000Z"
     *   }
     * }
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 403 scenario="Someone else's order" {"status": 403, "message": "You don't have permission to access this resource"}
     * @response 404 scenario="Order not found" {"status": 404, "message": "Resource not found"}
     * @response 409 scenario="Order is not pending" {"status": 409, "message": "Only pending orders can have their details or items changed"}
     * @response 422 scenario="Status change not allowed" {"status": 422, "message": "Order status cannot change from confirmed to pending"}
     * @response 422 scenario="Empty update" {"status": 422, "message": "Send at least one field to update", "error": {"order": ["Send at least one field to update"]}}
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        Gate::authorize('update', $order);

        $order = $this->orderService->update($order, UpdateOrderDTO::fromRequest(
            customerName: $request->customer_name,
            customerEmail: $request->customer_email,
            customerPhone: $request->customer_phone,
            status: $request->enum('status', OrderStatus::class),
            items: $request->has('items') ? OrderItemDTO::collectionFromRequest($request->items) : null,
        ));

        return response()->api(
            message: __('message.updated_successfully'),
            data: BaseResource::make(OrderResource::make($order)),
        );
    }

    /**
     * Delete an order
     *
     * Deletes the order and its items. Orders that have any payment (even a failed one) cannot be deleted.
     *
     * @urlParam id string required The order ID (UUID). Example: 01a15fbc-af62-704f-8643-f69b490d1ea9
     *
     * @response 200 scenario="Deleted" {"status": 200, "message": "Deleted successfully"}
     * @response 401 scenario="Missing or invalid token" {"status": 401, "message": "Unauthenticated, please login first"}
     * @response 403 scenario="Someone else's order" {"status": 403, "message": "You don't have permission to access this resource"}
     * @response 404 scenario="Order not found" {"status": 404, "message": "Resource not found"}
     * @response 409 scenario="Order has payments" {"status": 409, "message": "Order cannot be deleted because it has payments"}
     */
    public function destroy(Order $order): JsonResponse
    {
        Gate::authorize('delete', $order);

        $this->orderService->delete($order);

        return response()->api(message: __('message.deleted_successfully'));
    }
}
