<?php

namespace App\Payments\Gateways;

use App\Payments\Contracts\PaymentGatewayInterface;

/**
 * Shared setup for gateways: holds the gateway's config block from config/payments.php.
 */
abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(protected readonly array $config)
    {
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
}
