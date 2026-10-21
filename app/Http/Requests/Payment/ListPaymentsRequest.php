<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Concerns\ValidatesPagination;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ListPaymentsRequest extends FormRequest
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
        return $this->paginationRules();
    }
}
