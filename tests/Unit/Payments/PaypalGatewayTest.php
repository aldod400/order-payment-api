<?php

namespace Tests\Unit\Payments;

use App\DTOs\Payment\ChargeDTO;
use App\Payments\Gateways\PaypalGateway;
use Tests\TestCase;

class PaypalGatewayTest extends TestCase
{
    private function charge(string $email): ChargeDTO
    {
        return new ChargeDTO(
            orderId: 'order-1',
            amount: '10.00',
            currency: 'USD',
            idempotencyKey: 'key-1',
            details: ['paypal_email' => $email],
        );
    }

    public function test_it_approves_a_normal_account(): void
    {
        $result = (new PaypalGateway(['decline_domain' => 'decline.test']))->charge($this->charge('buyer@example.com'));

        $this->assertTrue($result->isSuccessful());
        $this->assertStringStartsWith('pp_', $result->transactionId);
        $this->assertSame('buyer@example.com', $result->response['payer_email']);
    }

    public function test_it_declines_an_account_on_the_decline_domain(): void
    {
        $result = (new PaypalGateway(['decline_domain' => 'decline.test']))->charge($this->charge('buyer@decline.test'));

        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->transactionId);
        $this->assertSame('PayPal payment declined', $result->response['message']);
    }

    public function test_it_requires_a_paypal_email(): void
    {
        $this->assertSame(['paypal_email'], array_keys((new PaypalGateway([]))->rules()));
    }
}
