<?php

namespace App\Http\Requests\Order\Concerns;

trait ValidatesOrderItems
{
    /**
     * @return array<string, array<int, string>>
     */
    protected function itemRules(): array
    {
        return [
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'items.*.price' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:9999999.99'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'items.*.product_name' => __('message.item_product_name'),
            'items.*.quantity' => __('message.item_quantity'),
            'items.*.price' => __('message.item_price'),
        ];
    }
}
