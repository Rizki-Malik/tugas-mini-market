<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Inventory;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::query()
            ->with(['inventory.product', 'inventory.store', 'user'])
            ->when($request->store_id, function($query, $storeId) {
                $query->whereHas('inventory', fn($q) => 
                    $q->where('store_id', $storeId));
            })
            ->when($request->product_id, function($query, $productId) {
                $query->whereHas('inventory', fn($q) => 
                    $q->where('product_id', $productId));
            })
            ->when($request->date_from, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(20);

        return view('inventory.movements', compact('movements'));
    }

    public function show(StockMovement $movement)
    {
        $movement->load(['inventory.product', 'inventory.store', 'user', 'relatedInventory.store']);
        return view('inventory.movement-details', compact('movement'));
    }
}