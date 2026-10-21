<?php

namespace App\Payments\Gateways;

use App\DTOs\Payment\ChargeDTO;
use App\DTOs\Payment\PaymentResultDTO;
use Illuminate\Support\Str;

/**
 * Simulated card processor. A card number ending in the configured
 * decline suffix (default "0002") is declined; every other card is approved.
 */
final class CreditCardGateway extends AbstractPaymentGateway
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'card_number' => ['required', 'digits_between:13,19'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:'.now()->year],
            'cvv' => ['required', 'digits_between:3,4'],
            'holder_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function charge(ChargeDTO $charge): PaymentResultDTO
    {
        $cardNumber = (string) $charge->details['card_number'];

        // Only the last 4 digits are ever kept.
        $response = [
            'gateway' => 'credit_card',
            'card_last4' => substr($cardNumber, -4),
            'amount' => $charge->amount,
            'currency' => $charge->currency,
        ];

        if (str_ends_with($cardNumber, (string) $this->config('decline_suffix', '0002'))) {
            return PaymentResultDTO::failed([...$response, 'message' => 'Card declined']);
        }

        return PaymentResultDTO::successful(
            'cc_'.Str::uuid(),
            [...$response, 'message' => 'Approved'],
        );
    }
}
