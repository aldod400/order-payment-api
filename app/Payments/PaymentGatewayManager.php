<?php

namespace App\Payments;

use App\Exceptions\Payment\UnsupportedPaymentMethodException;
use App\Payments\Contracts\PaymentGatewayInterface;
use App\Payments\Contracts\PaymentGatewayManagerInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container;

/**
 * Picks the payment strategy by method name. Gateways are registered
 * only in config/payments.php, so adding one needs no code change here.
 */
final class PaymentGatewayManager implements PaymentGatewayManagerInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly Config $config,
    ) {}

    public function resolve(string $method): PaymentGatewayInterface
    {
        $gatewayConfig = $this->config->get("payments.gateways.{$method}");
        $class = $gatewayConfig['class'] ?? null;

        if (! is_string($class) || ! is_subclass_of($class, PaymentGatewayInterface::class)) {
            throw new UnsupportedPaymentMethodException($method);
        }

        return $this->container->make($class, ['config' => $gatewayConfig]);
    }

    /**
     * @return list<string>
     */
    public function methods(): array
    {
        return array_keys($this->config->get('payments.gateways', []));
    }
}
