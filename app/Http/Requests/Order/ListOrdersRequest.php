<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatus;
use App\Http\Requests\Concerns\ValidatesPagination;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListOrdersRequest extends FormRequest
{
    use ValidatesPagination;

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
            'status' => ['sometimes', Rule::enum(OrderStatus::class)],
            ...$this->paginationRules(),
        ];
    }
}
