<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::all();
        $transactions = Transaction::with(['store', 'items.product']);

        if ($request->filled('store_id')) {
            $transactions->where('store_id', $request->store_id);
        }
        if ($request->filled('date_from')) {
            $transactions->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $transactions->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $transactions->paginate(10);

        return view('transactions.index', compact('stores', 'transactions'));
    }

    public function create()
    {
        $stores = Store::with(['inventories.product'])->get();

        foreach ($stores as $store) {
            foreach ($store->inventories as $inventory) {
                $inventory->product->inventory_quantity = $inventory->quantity;
            }
        }

        return view('transactions.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($validated['items'] as $item) {
            $inventory = Inventory::where('store_id', $validated['store_id'])
                ->where('product_id', $item['product_id'])
                ->first();

            if (!$inventory) {
                return back()->withErrors(['message' => 'Inventory not found for product ID: ' . $item['product_id']]);
            }

            if ($inventory->quantity < $item['quantity']) {
                return back()->withErrors(['message' => 'Insufficient stock for product ID: ' . $item['product_id']]);
            }
        }

        try {
            DB::transaction(function () use ($validated) {
                $transaction = $this->createTransaction($validated);
                $this->processTransactionItems($transaction, $validated['items']);
            });

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('transactions.create')
                ->withErrors(['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['store', 'items.product']);
        return view('transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['store', 'items.product']);
        return view('transactions.print', compact('transaction'));
    }

    private function createTransaction(array $data): Transaction
    {
        $totalAmount = collect($data['items'])->reduce(function ($total, $item) {
            $product = Product::find($item['product_id']);
            return $total + ($product->price * $item['quantity']);
        }, 0);

        return Transaction::create([
            'store_id' => $data['store_id'],
            'user_id' => auth()->id(),
            'invoice_number' => 'INV-' . now()->timestamp,
            'total_amount' => $totalAmount,
        ]);
    }

    private function processTransactionItems(Transaction $transaction, array $items)
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];

            $transaction->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            $inventory = Inventory::where('store_id', $transaction->store_id)
                ->where('product_id', $item['product_id'])
                ->firstOrFail();

            $inventory->decrement('quantity', $item['quantity']);
        }
    }
}