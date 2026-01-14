<?php

namespace App\Http\Requests;

use App\Models\InventoryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DeductInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.notes' => 'nullable|string|max:500',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $items = $this->input('items', []);
                
                foreach ($items as $index => $item) {
                    if (!isset($item['inventory_item_id']) || !isset($item['quantity'])) {
                        continue;
                    }

                    $inventoryItem = InventoryItem::find($item['inventory_item_id']);
                    
                    if ($inventoryItem && $item['quantity'] > $inventoryItem->quantity) {
                        $validator->errors()->add(
                            "items.{$index}.quantity",
                            "Insufficient quantity. Available: {$inventoryItem->quantity}"
                        );
                    }
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required.',
            'items.*.inventory_item_id.required' => 'Item is required.',
            'items.*.inventory_item_id.exists' => 'Selected item is invalid.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.gt' => 'Quantity must be greater than 0.',
        ];
    }
}
