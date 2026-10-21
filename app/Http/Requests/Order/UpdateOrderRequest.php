<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatus;
use App\Http\Requests\Order\Concerns\ValidatesOrderItems;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateOrderRequest extends FormRequest
{
    use ValidatesOrderItems;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['sometimes', 'string', 'max:255'],
            'customer_email' => ['sometimes', 'string', 'email:rfc', 'max:255'],
            'customer_phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'status' => ['sometimes', Rule::enum(OrderStatus::class)],
            'items' => ['sometimes', 'array', 'min:1', 'max:100'],
            ...$this->itemRules(),
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (! $this->hasAny(['customer_name', 'customer_email', 'customer_phone', 'status', 'items'])) {
                    $validator->errors()->add('order', __('message.nothing_to_update'));
                }
            },
        ];
    }
}
