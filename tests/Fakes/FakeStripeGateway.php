<?php

namespace Tests\Fakes;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\Payments\Gateways\AbstractPaymentGateway;

/**
 * A "new" gateway used to prove that adding one needs only a class and a config entry.
 */
final class FakeStripeGateway extends AbstractPaymentGateway
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return ['token' => ['required', 'string']];
    }

    public function charge(ChargeDTO $charge): PaymentResultDTO
    {
        return PaymentResultDTO::successful('st_'.$charge->orderId, [
            'gateway' => 'stripe',
            'key_used' => $this->config('key'),
        ]);
    }
}
