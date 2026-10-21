<?php

namespace Tests\Unit\Payments;

use App\DTOs\Payment\ChargeDTO;
use App\Exceptions\Payment\UnsupportedPaymentMethodException;
use App\Payments\Contracts\PaymentGatewayManagerInterface;
use App\Payments\Gateways\CreditCardGateway;
use App\Payments\Gateways\PaypalGateway;
use App\Payments\PaymentGatewayManager;
use stdClass;
use Tests\Fakes\FakeStripeGateway;
use Tests\TestCase;

class PaymentGatewayManagerTest extends TestCase
{
    private function manager(): PaymentGatewayManagerInterface
    {
        return $this->app->make(PaymentGatewayManagerInterface::class);
    }

    public function test_the_interface_is_bound_to_the_manager(): void
    {
        $this->assertInstanceOf(PaymentGatewayManager::class, $this->manager());
    }

    public function test_it_resolves_each_configured_gateway(): void
    {
        $this->assertInstanceOf(CreditCardGateway::class, $this->manager()->resolve('credit_card'));
        $this->assertInstanceOf(PaypalGateway::class, $this->manager()->resolve('paypal'));
    }

    public function test_it_lists_the_configured_methods(): void
    {
        $this->assertSame(['credit_card', 'paypal'], $this->manager()->methods());
    }

    public function test_it_rejects_an_unknown_method(): void
    {
        $this->expectException(UnsupportedPaymentMethodException::class);

        $this->manager()->resolve('bitcoin');
    }

    public function test_it_rejects_a_class_that_is_not_a_gateway(): void
    {
        config(['payments.gateways.broken' => ['class' => stdClass::class]]);

        $this->expectException(UnsupportedPaymentMethodException::class);

        $this->manager()->resolve('broken');
    }

    public function test_a_new_gateway_works_with_only_a_config_entry(): void
    {
        config(['payments.gateways.stripe' => [
            'class' => FakeStripeGateway::class,
            'key' => 'sk_test_123',
        ]]);

        $gateway = $this->manager()->resolve('stripe');
        $result = $gateway->charge(new ChargeDTO('order-9', '5.00', 'USD', 'key-1', ['token' => 'tok']));

        $this->assertContains('stripe', $this->manager()->methods());
        $this->assertInstanceOf(FakeStripeGateway::class, $gateway);
        $this->assertSame('st_order-9', $result->transactionId);
        $this->assertSame('sk_test_123', $result->response['key_used']);
    }
}
