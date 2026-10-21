<?php

namespace App\Payments\Contracts;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentResultDTO;

interface PaymentGatewayInterface
{
    /**
     * Validation rules for the gateway-specific "details" field.
     *
     * @return array<string, mixed>
     */
    public function rules(): array;

    public function charge(ChargeDTO $charge): PaymentResultDTO;
}
