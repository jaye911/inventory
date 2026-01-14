<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function createItems(array $items): array
    {
        return DB::transaction(function () use ($items) {
            $createdItems = [];
            
            foreach ($items as $itemData) {
                $item = InventoryItem::create([
                    'name' => $itemData['name'],
                    'description' => $itemData['description'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit_id' => $itemData['unit_id'],
                ]);

                if ($itemData['quantity'] > 0) {
                    InventoryTransaction::create([
                        'inventory_item_id' => $item->id,
                        'type' => 'addition',
                        'quantity' => $itemData['quantity'],
                        'quantity_before' => 0,
                        'quantity_after' => $itemData['quantity'],
                        'notes' => 'Initial stock',
                        'user_id' => Auth::id(),
                    ]);
                }

                $createdItems[] = $item;
            }

            return $createdItems;
        });
    }

    public function addStock(array $items): array
    {
        return DB::transaction(function () use ($items) {
            $transactions = [];

            foreach ($items as $itemData) {
                $inventoryItem = InventoryItem::lockForUpdate()->find($itemData['inventory_item_id']);
                
                $quantityBefore = $inventoryItem->quantity;
                $quantityAfter = $quantityBefore + $itemData['quantity'];

                $inventoryItem->update(['quantity' => $quantityAfter]);

                $transaction = InventoryTransaction::create([
                    'inventory_item_id' => $inventoryItem->id,
                    'type' => 'addition',
                    'quantity' => $itemData['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $quantityAfter,
                    'notes' => $itemData['notes'] ?? null,
                    'user_id' => Auth::id(),
                ]);

                $transactions[] = $transaction;
            }

            return $transactions;
        });
    }

    public function deductStock(array $items): array
    {
        return DB::transaction(function () use ($items) {
            $transactions = [];

            foreach ($items as $itemData) {
                $inventoryItem = InventoryItem::lockForUpdate()->find($itemData['inventory_item_id']);
                
                if ($inventoryItem->quantity < $itemData['quantity']) {
                    throw new \Exception("Insufficient quantity for item: {$inventoryItem->name}");
                }

                $quantityBefore = $inventoryItem->quantity;
                $quantityAfter = $quantityBefore - $itemData['quantity'];

                $inventoryItem->update(['quantity' => $quantityAfter]);

                $transaction = InventoryTransaction::create([
                    'inventory_item_id' => $inventoryItem->id,
                    'type' => 'deduction',
                    'quantity' => $itemData['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $quantityAfter,
                    'notes' => $itemData['notes'] ?? null,
                    'user_id' => Auth::id(),
                ]);

                $transactions[] = $transaction;
            }

            return $transactions;
        });
    }
}
