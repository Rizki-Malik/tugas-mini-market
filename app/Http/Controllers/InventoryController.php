<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Store;
use App\Models\StockMovement;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::all();
        
        $inventory = Inventory::query()
            ->with(['product', 'store'])
            ->when($request->store_id, fn($query, $storeId) => 
                $query->where('store_id', $storeId))
            ->when($request->search, fn($query, $search) => 
                $query->whereHas('product', fn($q) => 
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                ))
            ->when($request->low_stock, fn($query) => 
                $query->whereRaw('quantity <= minimum_stock'))
            ->paginate(15);

        \Log::info('Inventory Items:', $inventory->toArray());

        return view('inventory.index', compact('inventory', 'stores'));
    }

    public function adjustStockForm(Inventory $inventory)
    {
        $inventory->load('product', 'store');
        return view('inventory.adjust-stock', compact('inventory'));
    }
    
    public function transferStockForm(Inventory $inventory)
    {
        $inventory->load('product', 'store');
        $stores = Store::where('id', '!=', $inventory->store_id)->get();
        return view('inventory.transfer-stock', compact('inventory', 'stores'));
    }

    public function adjustStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer',
            'adjustment_type' => 'required|in:add,subtract,set',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($inventory, $validated) {
                $oldQuantity = $inventory->quantity;

                match ($validated['adjustment_type']) {
                    'add' => $inventory->increment('quantity', $validated['quantity']),
                    'subtract' => $this->handleSubtraction($inventory, $validated['quantity']),
                    'set' => $inventory->update(['quantity' => $validated['quantity']]),
                };                

                $this->recordStockMovement([
                    'inventory_id' => $inventory->id,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $inventory->fresh()->quantity,
                    'movement_type' => $validated['adjustment_type'],
                    'notes' => $validated['notes'] ?? '',
                    'user_id' => auth()->id(),
                ]);
            });

            return redirect()->route('inventory.index')->with('success', 'Stock adjusted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_inventory_id' => 'required|exists:inventories,id',
            'to_store_id' => 'required|exists:stores,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $fromInventory = Inventory::findOrFail($validated['from_inventory_id']);

                $this->handleSubtraction($fromInventory, $validated['quantity']);

                $toInventory = Inventory::firstOrCreate(
                    [
                        'store_id' => $validated['to_store_id'],
                        'product_id' => $fromInventory->product_id,
                    ],
                    ['quantity' => 0, 'minimum_stock' => $fromInventory->minimum_stock]
                );

                $toInventory->increment('quantity', $validated['quantity']);

                $this->recordStockMovement([
                    'inventory_id' => $fromInventory->id,
                    'previous_quantity' => $fromInventory->quantity + $validated['quantity'],
                    'new_quantity' => $fromInventory->quantity,
                    'movement_type' => 'transfer_out',
                    'notes' => $validated['notes'] ?? '',
                    'user_id' => auth()->id(),
                    'related_inventory_id' => $toInventory->id,
                ]);

                $this->recordStockMovement([
                    'inventory_id' => $toInventory->id,
                    'previous_quantity' => $toInventory->quantity - $validated['quantity'],
                    'new_quantity' => $toInventory->quantity,
                    'movement_type' => 'transfer_in',
                    'notes' => $validated['notes'] ?? '',
                    'user_id' => auth()->id(),
                    'related_inventory_id' => $fromInventory->id,
                ]);
            });

            return redirect()->route('inventory.index')->with('success', 'Stock transferred successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    public function lowStock()
    {
        $inventory = Inventory::query()
            ->with(['product', 'store'])
            ->whereRaw('quantity <= minimum_stock')
            ->paginate(15);

        return view('inventory.low-stock', compact('inventory'));
    }

    private function handleSubtraction(Inventory $inventory, int $quantity)
    {
        if ($inventory->quantity < $quantity) {
            throw new \Exception('Cannot subtract more than available quantity');
        }
        $inventory->decrement('quantity', $quantity);
    }

    private function recordStockMovement(array $data)
    {
        StockMovement::create([
            'inventory_id' => $data['inventory_id'],
            'previous_quantity' => $data['previous_quantity'],
            'new_quantity' => $data['new_quantity'],
            'movement_type' => $data['movement_type'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id'],
            'related_inventory_id' => $data['related_inventory_id'] ?? null,
        ]);
    }

}