<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_id' => 'required|exists:units,id',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required.',
            'items.*.name.required' => 'Item name is required.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.min' => 'Quantity must be at least 0.',
            'items.*.unit_id.required' => 'Unit is required.',
            'items.*.unit_id.exists' => 'Selected unit is invalid.',
        ];
    }
}
