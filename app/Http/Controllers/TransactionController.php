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
        $transactions = Transaction::query();

        if ($request->has('store_id') && $request->store_id) {
            $transactions->where('store_id', $request->store_id);
        }
        if ($request->has('date_from') && $request->date_from) {
            $transactions->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $transactions->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $transactions->paginate(10);

        return view('transactions.index', compact('stores', 'transactions'));
    }

    public function create()
    {
        $stores = Store::all();
        $products = Product::all();

        return view('transactions.create', compact('stores', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                $total = 0;
                $items = collect($validated['items'])->map(function ($item) use (&$total) {
                    $product = Product::findOrFail($item['product_id']);
                    $subtotal = $product->price * $item['quantity'];
                    $total += $subtotal;

                    return [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                });

                // Create transaction
                $transaction = Transaction::create([
                    'store_id' => $validated['store_id'],
                    'user_id' => auth()->id(),
                    'invoice_number' => 'INV-' . time(),
                    'total_amount' => $total,
                ]);

                // Add items and update inventory
                foreach ($items as $item) {
                    $transaction->items()->create($item);
                    
                    // Update inventory
                    $inventory = Inventory::where('store_id', $validated['store_id'])
                        ->where('product_id', $item['product_id'])
                        ->firstOrFail();
                    
                    if ($inventory->quantity < $item['quantity']) {
                        throw new \Exception('Insufficient stock for product ID: ' . $item['product_id']);
                    }
                    
                    $inventory->decrement('quantity', $item['quantity']);
                }

                return response()->json([
                    'status' => 'success',
                    'transaction' => $transaction->load('items.product'),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'store']);

        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['items.product', 'store']);

        return view('transactions.print', [
            'transaction' => $transaction,
        ]);
    }

}
