<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Order\Concerns\ValidatesOrderItems;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'string', 'email:rfc', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            ...$this->itemRules(),
        ];
    }
}
