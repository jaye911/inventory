<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'quantity_before' => 'decimal:4',
        'quantity_after' => 'decimal:4',
    ];

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAddition(): bool
    {
        return $this->type === 'addition';
    }

    public function isDeduction(): bool
    {
        return $this->type === 'deduction';
    }
}
