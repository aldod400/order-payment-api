<?php

namespace App\Payments\Contracts;

use App\Payments\PaymentGatewayManager;
use Illuminate\Container\Attributes\Bind;

#[Bind(PaymentGatewayManager::class)]
interface PaymentGatewayManagerInterface
{
    public function resolve(string $method): PaymentGatewayInterface;

    /**
     * @return list<string>
     */
    public function methods(): array;
}
