<?php

namespace App\Payments\Gateways;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentResultDTO;
use Illuminate\Support\Str;

/**
 * Simulated PayPal processor. An account on the configured decline
 * domain (default "decline.test") is declined; every other account is approved.
 */
final class PaypalGateway extends AbstractPaymentGateway
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'paypal_email' => ['required', 'email', 'max:255'],
        ];
    }

    public function charge(ChargeDTO $charge): PaymentResultDTO
    {
        $email = (string) $charge->details['paypal_email'];

        $response = [
            'gateway' => 'paypal',
            'payer_email' => $email,
            'amount' => $charge->amount,
            'currency' => $charge->currency,
        ];

        if (str_ends_with($email, '@'.$this->config('decline_domain', 'decline.test'))) {
            return PaymentResultDTO::failed([...$response, 'message' => 'PayPal payment declined']);
        }

        return PaymentResultDTO::successful(
            'pp_'.Str::uuid(),
            [...$response, 'message' => 'Approved'],
        );
    }
}
