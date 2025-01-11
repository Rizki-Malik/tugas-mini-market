<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Store::create($request->only(['name', 'address', 'city']));
        return redirect()->route('stores.index')->with('success', 'Store created successfully!');
    }

    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $store->update($request->only(['name', 'address', 'city']));
        return redirect()->route('stores.index')->with('success', 'Store updated successfully!');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Store deleted successfully!');
    }

    public function products(Store $store)
    {
        $products = $store->products()->with('inventories')->get();

        foreach ($products as $product) {
            $inventory = $store->inventories->where('product_id', $product->id)->first();
            $product->quantity = $inventory ? $inventory->quantity : 0;
        }

        return view('stores.products', compact('store', 'products'));
    }


    public function addProducts(Store $store)
    {
        $products = Product::all();
        return view('stores.add-products', compact('store', 'products'));
    }

    public function storeProducts(Request $request, Store $store)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($request->products as $productData) {
            $inventory = Inventory::firstOrNew([
                'store_id' => $store->id,
                'product_id' => $productData['id'],
            ]);

            $inventory->quantity += $productData['quantity'];
            $inventory->minimum_stock = $inventory->minimum_stock ?? 0;
            $inventory->save();
        }

        return redirect()->route('stores.index')->with('success', 'Products added to inventory successfully.');
    }
}