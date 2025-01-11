<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Generate summary for sales report.
     */
    private function generateSalesSummary($sales)
    {
        $salesByStore = $sales->groupBy('store_id')->map(function ($storeSales, $storeId) {
            $storeName = $storeSales->first()->store->name ?? 'Unknown Store';
            return [
                'store_name' => $storeName,
                'total' => $storeSales->sum('total_amount'),
                'count' => $storeSales->count(),
            ];
        });

        return [
            'total_sales' => $sales->sum('total_amount'),
            'total_transactions' => $sales->count(),
            'average_transaction' => $sales->average('total_amount'),
            'sales_by_store' => $salesByStore,
        ];
    }

    /**
     * Display sales report.
     */
    public function index(Request $request)
    {
        $salesQuery = Transaction::query();

        if ($request->has('store_id') && $request->store_id) {
            $salesQuery->where('store_id', $request->store_id);
        }
        if ($request->has('date_from') && $request->date_from) {
            $salesQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $salesQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $salesQuery->get();
        $summary = $this->generateSalesSummary($sales);

        return view('reports.index', compact('sales', 'summary'));
    }

    /**
     * Display inventory report.
     */
    public function inventoryReport(Request $request)
    {
        $salesQuery = Transaction::query();

        if ($request->has('store_id') && $request->store_id) {
            $salesQuery->where('store_id', $request->store_id);
        }

        $storeId = $salesQuery->first()?->store_id;
        $inventory = Inventory::query()
            ->when($storeId, fn($query, $storeId) => $query->where('store_id', $storeId))
            ->with(['product', 'store'])
            ->get();

        $summary = $this->generateInventorySummary($inventory);

        return view('reports.inventory', compact('inventory', 'summary'));
    }

    /**
     * Display transaction report.
     */
    public function transactionReport(Request $request)
    {
        $salesQuery = Transaction::query();

        if ($request->has('store_id') && $request->store_id) {
            $salesQuery->where('store_id', $request->store_id);
        }
        if ($request->has('date_from') && $request->date_from) {
            $salesQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $salesQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $salesQuery->get();
        $transactions = Transaction::query()
            ->when($sales->pluck('store_id')->unique(), fn($query, $storeIds) => $query->whereIn('store_id', $storeIds))
            ->whereBetween('created_at', [$request->date_from, $request->date_to])
            ->with(['store', 'items.product'])
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_amount' => $transactions->sum('total_amount'),
            'average_transaction' => $transactions->average('total_amount'),
            'transactions_by_status' => $transactions->groupBy('status')->map(fn($statusGroup) => [
                'count' => $statusGroup->count(),
                'total' => $statusGroup->sum('total_amount'),
            ]),
            'transactions_by_store' => $transactions->groupBy('store_id')->map(fn($storeGroup) => [
                'count' => $storeGroup->count(),
                'total' => $storeGroup->sum('total_amount'),
            ]),
        ];

        return view('reports.transactions', compact('transactions', 'summary'));
    }

    /**
     * Export sales report.
     */
    public function exportSales(Request $request)
    {
        // Implement export logic (e.g., generate CSV or Excel)
        return response()->json(['message' => 'Sales export functionality is under development.']);
    }

    /**
     * Export inventory report.
     */
    public function exportInventory(Request $request)
    {
        // Implement export logic (e.g., generate CSV or Excel)
        return response()->json(['message' => 'Inventory export functionality is under development.']);
    }

    /**
     * Generate summary for inventory report.
     */
    private function generateInventorySummary($inventory)
    {
        return [
            'total_products' => $inventory->count(),
            'low_stock_items' => $inventory->where('quantity', '<=', 10)->count(),
            'out_of_stock_items' => $inventory->where('quantity', 0)->count(),
            'inventory_by_store' => $inventory->groupBy('store_id')->map(fn($storeInventory) => [
                'total_items' => $storeInventory->count(),
                'low_stock' => $storeInventory->where('quantity', '<=', 10)->count(),
            ]),
        ];
    }
}