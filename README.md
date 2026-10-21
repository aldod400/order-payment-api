# Order & Payment Management API

A Laravel 13 REST API for managing orders and payments, secured with JWT. Payment gateways use the strategy pattern: a new gateway is one class plus one config entry, with no changes to existing code.

- **Postman collection:** [`docs/postman/Order-Payment-API.postman_collection.json`](docs/postman/Order-Payment-API.postman_collection.json)
- **OpenAPI spec:** [`docs/openapi.yaml`](docs/openapi.yaml)
- **HTML docs:** `http://localhost:8000/docs` while the app is running

---

## Contents

1. [Setup](#setup)
2. [Running the tests](#running-the-tests)
3. [Using the API](#using-the-api)
4. [Payment gateways: how they work and how to add one](#payment-gateways)
5. [Design notes](#design-notes)
6. [Assumptions](#assumptions)
7. [Project structure](#project-structure)

---

## Setup

**Requirements:** PHP 8.3+ with the `bcmath`, `pdo_mysql` and `pdo_sqlite` extensions, Composer, and MySQL 8 / MariaDB 10.6+.

```bash
git clone <repository-url> order-payment-api
cd order-payment-api

composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Create a database, then set the connection in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tocaan_order_payment
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate
php artisan serve          # http://localhost:8000
```

If `bcmath` is missing, Composer reports it during install. On Ubuntu, install it with `sudo apt install php8.4-bcmath`.

---

## Running the tests

```bash
php artisan test
```

The tests use an in-memory SQLite database, so they never touch your MySQL data.

| Suite | What it covers |
|---|---|
| `tests/Unit/Payments` | Each gateway approves and declines correctly and never keeps the full card number. The manager picks the right gateway, rejects unknown methods, and accepts a new gateway added only through config. |
| `tests/Feature/Auth` | Register, login, wrong credentials, `me`, refresh (the old token stops working), logout (the token stops working), rate limiting, Arabic messages. |
| `tests/Feature/Order` | Create (server-side totals), validation, pagination and status filter, ownership (403), 404s, partial updates, replacing items, every status transition, the delete rules. |
| `tests/Feature/Payment` | Card and PayPal payments, the confirmed-only rule, declines and retries, idempotent retries, double-payment prevention, gateway-specific validation, gateway crashes, a new gateway end to end, ownership on every endpoint. |

---

## Using the API

All endpoints are under `/api/v1`. Send `Accept: application/json` with every request. Every endpoint except register and login also needs `Authorization: Bearer {token}`.

### Endpoints

| Method | URL | Description |
|---|---|---|
| POST | `/auth/register` | Create an account and get a token |
| POST | `/auth/login` | Get a token |
| GET | `/auth/me` | The current user |
| POST | `/auth/refresh` | Swap the token for a new one; the old one stops working |
| POST | `/auth/logout` | Invalidate the token |
| GET | `/orders?status=&page=&per_page=` | Your orders, paginated, optionally filtered by status |
| POST | `/orders` | Create an order; totals are calculated on the server |
| GET | `/orders/{id}` | One order with its items |
| PUT/PATCH | `/orders/{id}` | Change details or items (pending orders only), or change the status |
| DELETE | `/orders/{id}` | Delete an order (only if it has no payments) |
| GET | `/orders/{id}/payments` | All payment attempts for an order |
| POST | `/orders/{id}/payments` | Pay a confirmed order (needs an `Idempotency-Key` header) |
| GET | `/payments` | All your payments, paginated |
| GET | `/payments/{id}` | One payment |

### A typical flow

```bash
# 1. Register (or log in) and keep the token
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"name":"Ali","email":"ali@example.com","password":"secret123","password_confirmation":"secret123"}'

# 2. Create an order
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" \
  -d '{"customer_name":"Ali","customer_email":"ali@example.com","items":[{"product_name":"Book","quantity":3,"price":19.99}]}'

# 3. Confirm it
curl -X PATCH http://localhost:8000/api/v1/orders/$ORDER_ID \
  -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" \
  -d '{"status":"confirmed"}'

# 4. Pay it
curl -X POST http://localhost:8000/api/v1/orders/$ORDER_ID/payments \
  -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" \
  -H "Idempotency-Key: $(uuidgen)" \
  -d '{"payment_method":"credit_card","details":{"card_number":"4111111111111111","expiry_month":12,"expiry_year":2030,"cvv":"123","holder_name":"Ali"}}'
```

The Postman collection does the same and saves the token automatically after register or login.

### Response format

Every response, success or error, uses the same envelope:

```json
{ "status": 200, "message": "Success", "data": { } }
```

- **Lists** add pagination fields next to `data`: `current_page`, `from`, `last_page`, `per_page`, `to`, `total` and `links`.
- **Validation errors (422)** put the first message in `message` and every field's errors under `error`.
- **Messages are translated.** Send `Accept-Language: ar` for Arabic; the default is English.

| Code | Meaning |
|---|---|
| 200 / 201 | Success / created |
| 401 | Missing, invalid or expired token, or wrong login details |
| 403 | The order or payment belongs to another user |
| 404 | Not found |
| 409 | Not allowed in the current state (order has payments, already paid, not editable) |
| 422 | Validation failed, or a business rule refused it (order not confirmed, status change not allowed) |
| 429 | Too many requests |

### Simulated gateways

| Method | `details` fields | Declined when |
|---|---|---|
| `credit_card` | `card_number`, `expiry_month`, `expiry_year`, `cvv`, `holder_name` | the card number ends in `0002` |
| `paypal` | `paypal_email` | the email ends in `@decline.test` |

You can change both triggers in `.env` (`CREDIT_CARD_DECLINE_SUFFIX`, `PAYPAL_DECLINE_DOMAIN`).

---

## Payment gateways

### How it works

```
PaymentController
  → ProcessPaymentRequest        validates payment_method against config, plus that gateway's own rules()
  → PaymentService               business rules, idempotency, locking, saving the payment
  → PaymentGatewayManager        picks the gateway class for payment_method from config/payments.php
  → PaymentGatewayInterface      the strategy: rules() + charge(ChargeDTO): PaymentResultDTO
      ├── CreditCardGateway
      └── PaypalGateway
```

- **The strategy contract** is [`PaymentGatewayInterface`](app/Payments/Contracts/PaymentGatewayInterface.php). It has two methods: `rules()` validates the gateway-specific `details`, and `charge()` performs the charge.
- **Gateways never see Eloquent models.** They receive a `ChargeDTO` and return a `PaymentResultDTO`, so they know nothing about the database.
- **The manager builds only the gateway that's needed**, through the service container, and passes it that gateway's config block (API keys and settings).
- **Gateways are registered in [`config/payments.php`](config/payments.php) and nowhere else.** The list of valid `payment_method` values is read from there too.

### Adding a new gateway (Stripe as an example)

**1. Create the class.**

```php
namespace App\Payments\Gateways;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentResultDTO;

final class StripeGateway extends AbstractPaymentGateway
{
    public function rules(): array
    {
        return ['token' => ['required', 'string']];
    }

    public function charge(ChargeDTO $charge): PaymentResultDTO
    {
        // Call Stripe with $this->config('secret') and $charge->amount, $charge->idempotencyKey ...

        return PaymentResultDTO::successful($stripeChargeId, ['gateway' => 'stripe']);
        // or PaymentResultDTO::failed(['message' => $reason]);
    }
}
```

**2. Register it in `config/payments.php`.**

```php
'stripe' => [
    'class' => StripeGateway::class,
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

**3. Add the credentials to `.env`.**

```dotenv
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

That's all. `payment_method: "stripe"` is now accepted, its `details.token` is validated, and payments go through Stripe. Validation, the manager, the service, the controllers and the database don't change. The test `test_a_new_gateway_works_end_to_end_with_only_a_config_entry` proves this with a fake Stripe gateway.

---

## Design notes

**Layers.** Each class has one job, and each layer depends only on interfaces.

| Layer | Responsibility |
|---|---|
| Form requests | Validate the shape of the input |
| Policies | Ownership: you can only touch your own orders and payments |
| Controllers | Build DTOs from the request, call a service, return a resource |
| Services | Business rules and transactions |
| Repositories | Data access only, one table each |
| Resources | The JSON shape of each response |

Interfaces are bound to their implementations with Laravel's `#[Bind]` attribute. Business errors extend `ApiException`, which declares its own status code. A single `ApiExceptionRenderer` turns every exception into the response envelope.

**Payment flow, safe against retries and races.**

1. In a short transaction, the order row is locked with `SELECT ... FOR UPDATE`, the rules are checked, and a `pending` payment is recorded.
2. The gateway is called **with no database lock held**, so a slow gateway never blocks the order.
3. The payment is updated to `successful` or `failed`. If the gateway throws an error, the payment is marked `failed`, so it never stays stuck in `pending`.

**Idempotency.** `POST /orders/{id}/payments` requires an `Idempotency-Key` header, which is stored with a unique index on `(order_id, idempotency_key)`. Repeating a request with the same key returns the original payment with **200** and never charges twice, even when two identical requests arrive at the same time.

**Concurrency.** Updating, deleting and paying an order all lock the order row first, so a payment can't start while the order is being cancelled or deleted.

**Money.** Amounts are stored as `DECIMAL`, calculated with `bcmath` (no floats), and returned as strings (`"59.97"`).

**Security.** JWT authentication, policy checks on every order and payment, UUIDs instead of guessable IDs, rate limits (5 per minute for login and register, 60 per minute otherwise), and full card numbers are never stored (only the last 4 digits).

---

## Assumptions

- Users manage only their own orders. There is no admin role.
- Customer details (`customer_name`, `customer_email`, `customer_phone`) are saved on each order, so an order keeps the details it was placed with.
- Totals are always calculated on the server. A `total` sent by the client is ignored.
- **Status flow:** `pending` → `confirmed` or `cancelled`. A `confirmed` order can be cancelled only while it's unpaid. A `cancelled` order is final.
- Customer details and items can be changed only while the order is `pending`. Sending `items` replaces all of them.
- In an update, a missing field means "no change". As a result, `customer_phone` can't be cleared through an update.
- A payment always charges the full order total. Partial payments and refunds are out of scope.
- A declined payment is still recorded (`failed`, HTTP 201), and the order can be paid again with a new idempotency key.
- "Orders cannot be deleted if they have associated payments" applies to **any** payment, including failed ones, because they're part of the order's financial history.
- Someone else's order returns **403**, not 404.
- The gateways are simulated, with predictable decline triggers. The API keys in `.env` are placeholders.
- One currency for the whole app (`PAYMENT_CURRENCY`, default `USD`).

**Out of scope, and how it would be done in production:** real gateways usually confirm payments asynchronously through webhooks. That would add a webhook endpoint per gateway that verifies the signature and stores each event's `event_id` with a unique index. The gateway retries webhooks, so already-seen events would be skipped.

---

## Project structure

```
app/
├── DTOs/                 Auth, Order and Payment data objects (incl. ChargeDTO, PaymentResultDTO)
├── Enums/                OrderStatus, PaymentStatus
├── Exceptions/           ApiException, ApiExceptionRenderer, Auth/, Order/, Payment/ business errors
├── Http/
│   ├── Controllers/Api/V1/   AuthController, OrderController, PaymentController
│   ├── Middleware/           SetLocale (Accept-Language)
│   ├── Requests/             Auth/, Order/, Payment/ form requests (+ shared Concerns/)
│   └── Resources/            User, Order, OrderItem, Payment resources
├── Models/               User, Order, OrderItem, Payment
├── Payments/             Gateway layer: Contracts/, Gateways/, PaymentGatewayManager
├── Policies/             OrderPolicy, PaymentPolicy
├── Providers/            AppServiceProvider, RateLimitServiceProvider, ResponseMacroServiceProvider
├── Repositories/         Contracts/ (interfaces) and Eloquent/ (implementations)
└── Services/             Contracts/ (interfaces) and Implementations/
config/payments.php       Gateway registry and credentials
docs/                     Postman collection and OpenAPI spec
lang/en, lang/ar          Translated messages
tests/                    Unit/Payments, Feature/{Auth,Order,Payment}, Fakes/
```
