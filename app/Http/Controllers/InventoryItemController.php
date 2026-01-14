<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddInventoryRequest;
use App\Http\Requests\DeductInventoryRequest;
use App\Http\Requests\StoreInventoryItemRequest;
use App\Http\Requests\UpdateInventoryItemRequest;
use App\Models\InventoryItem;
use App\Models\Unit;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryItemController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $items = InventoryItem::with('unit')
            ->search($search)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Index', [
            'items' => $items,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        $units = Unit::orderBy('name')->get();

        return Inertia::render('Inventory/Create', [
            'units' => $units,
        ]);
    }

    public function store(StoreInventoryItemRequest $request)
    {
        $this->inventoryService->createItems($request->validated()['items']);

        return redirect()->route('inventory.index')
            ->with('success', 'Items added successfully.');
    }

    public function show(InventoryItem $inventory)
    {
        $inventory->load(['unit', 'transactions.user']);
        
        $transactions = $inventory->transactions()->with('user')->paginate(20);

        return Inertia::render('Inventory/Show', [
            'item' => $inventory,
            'transactions' => $transactions,
        ]);
    }

    public function edit(InventoryItem $inventory)
    {
        $inventory->load('unit');
        $units = Unit::orderBy('name')->get();

        return Inertia::render('Inventory/Edit', [
            'item' => $inventory,
            'units' => $units,
        ]);
    }

    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventory)
    {
        $inventory->update($request->validated());

        return redirect()->route('inventory.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function addStock()
    {
        $items = InventoryItem::with('unit')->orderBy('name')->get();

        return Inertia::render('Inventory/AddStock', [
            'inventoryItems' => $items,
        ]);
    }

    public function storeAddStock(AddInventoryRequest $request)
    {
        $this->inventoryService->addStock($request->validated()['items']);

        return redirect()->route('inventory.index')
            ->with('success', 'Stock added successfully.');
    }

    public function deductStock()
    {
        $items = InventoryItem::with('unit')
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return Inertia::render('Inventory/DeductStock', [
            'inventoryItems' => $items,
        ]);
    }

    public function storeDeductStock(DeductInventoryRequest $request)
    {
        $this->inventoryService->deductStock($request->validated()['items']);

        return redirect()->route('inventory.index')
            ->with('success', 'Stock deducted successfully.');
    }
}
