<?php

namespace App\Http\Requests\Payment;

use App\Payments\Contracts\PaymentGatewayManagerInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['idempotency_key' => $this->header('Idempotency-Key')]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(PaymentGatewayManagerInterface $gateways): array
    {
        $rules = [
            'idempotency_key' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', Rule::in($gateways->methods())],
            'details' => ['required', 'array'],
        ];

        $method = $this->input('payment_method');

        if (! in_array($method, $gateways->methods(), true)) {
            return $rules;
        }

        $detailRules = collect($gateways->resolve($method)->rules())
            ->mapWithKeys(fn (array $fieldRules, string $field) => ["details.{$field}" => $fieldRules])
            ->all();

        return [...$rules, ...$detailRules];
    }
}
