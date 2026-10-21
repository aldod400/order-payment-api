<?php

namespace App\Http\Requests\Concerns;

trait ValidatesPagination
{
    /**
     * @return array<string, array<int, string>>
     */
    protected function paginationRules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
