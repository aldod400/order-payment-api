<?php

namespace Tests\Unit\Payments;

use App\DTOs\Payment\ChargeDTO;
use App\Enums\PaymentStatus;
use App\Payments\Gateways\CreditCardGateway;
use Tests\TestCase;

class CreditCardGatewayTest extends TestCase
{
    private function charge(string $cardNumber): ChargeDTO
    {
        return new ChargeDTO(
            orderId: 'order-1',
            amount: '59.97',
            currency: 'USD',
            idempotencyKey: 'key-1',
            details: ['card_number' => $cardNumber],
        );
    }

    public function test_it_approves_a_normal_card(): void
    {
        $result = (new CreditCardGateway(['decline_suffix' => '0002']))->charge($this->charge('4111111111111111'));

        $this->assertTrue($result->isSuccessful());
        $this->assertSame(PaymentStatus::SUCCESSFUL, $result->status);
        $this->assertStringStartsWith('cc_', $result->transactionId);
        $this->assertSame('59.97', $result->response['amount']);
    }

    public function test_it_declines_a_card_ending_with_the_decline_suffix(): void
    {
        $result = (new CreditCardGateway(['decline_suffix' => '0002']))->charge($this->charge('4000000000000002'));

        $this->assertFalse($result->isSuccessful());
        $this->assertSame(PaymentStatus::FAILED, $result->status);
        $this->assertNull($result->transactionId);
        $this->assertSame('Card declined', $result->response['message']);
    }

    public function test_the_decline_suffix_comes_from_config(): void
    {
        $result = (new CreditCardGateway(['decline_suffix' => '1111']))->charge($this->charge('4111111111111111'));

        $this->assertFalse($result->isSuccessful());
    }

    public function test_it_keeps_only_the_last_four_digits_of_the_card(): void
    {
        $result = (new CreditCardGateway([]))->charge($this->charge('4111111111111111'));

        $this->assertSame('1111', $result->response['card_last4']);
        $this->assertStringNotContainsString('4111111111111111', json_encode($result->response));
    }

    public function test_it_requires_the_card_fields(): void
    {
        $rules = (new CreditCardGateway([]))->rules();

        $this->assertEqualsCanonicalizing(
            ['card_number', 'expiry_month', 'expiry_year', 'cvv', 'holder_name'],
            array_keys($rules),
        );
    }
}
