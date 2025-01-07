<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory.
     */
    public function index(Request $request)
    {
        $inventory = Inventory::query()
            ->with(['product', 'store'])
            ->when($request->store_id, fn($query, $storeId) => $query->where('store_id', $storeId))
            ->when($request->low_stock, fn($query) => $query->where('quantity', '<=', 10))
            ->paginate(15);

        return view('inventory.index', compact('inventory'));
    }

    /**
     * Update the specified inventory resource.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'adjustment_type' => 'required|in:add,subtract,set',
        ]);

        try {
            DB::transaction(function () use ($inventory, $validated) {
                match ($validated['adjustment_type']) {
                    'add' => $inventory->increment('quantity', $validated['quantity']),
                    'subtract' => $this->handleSubtraction($inventory, $validated['quantity']),
                    'set' => $inventory->update(['quantity' => $validated['quantity']]),
                };
            });

            return $this->jsonResponse('success', $inventory->fresh());
        } catch (\Exception $e) {
            return $this->jsonResponse('error', null, $e->getMessage(), 422);
        }
    }

    /**
     * Show inventory items with low stock.
     */
    public function lowStock()
    {
        $lowStockItems = Inventory::where('quantity', '<=', 10)
            ->with(['product', 'store'])
            ->paginate(15);

        return view('inventory.low-stock', compact('lowStockItems'));
    }

    /**
     * Handle subtraction logic.
     */
    private function handleSubtraction(Inventory $inventory, int $quantity)
    {
        if ($inventory->quantity < $quantity) {
            throw new \Exception('Cannot subtract more than available quantity');
        }
        $inventory->decrement('quantity', $quantity);
    }

    /**
     * Helper for JSON responses.
     */
    private function jsonResponse(string $status, $data = null, string $message = '', int $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }
}