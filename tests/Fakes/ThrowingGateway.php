<?php

namespace Tests\Fakes;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\Payments\Gateways\AbstractPaymentGateway;
use RuntimeException;

/**
 * Simulates a gateway that is down (timeout, network error).
 */
final class ThrowingGateway extends AbstractPaymentGateway
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    public function charge(ChargeDTO $charge): PaymentResultDTO
    {
        throw new RuntimeException('Gateway timeout');
    }
}
