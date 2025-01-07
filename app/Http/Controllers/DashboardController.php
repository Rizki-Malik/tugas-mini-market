<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Transaction;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $storeId = null;
        if (auth()->user()->hasRole('store_manager')) {
            $storeId = auth()->user()->store_id;
        }

        $today = Carbon::today();
        $todayStats = $this->getTodayStats($storeId);
        
        $weeklyStats = $this->getWeeklyStats($storeId);
        
        $lowStockItems = $this->getLowStockItems($storeId);

        return view('dashboard', compact(
            'todayStats',
            'weeklyStats',
            'lowStockItems'
        ));
    }

    private function getTodayStats($storeId = null)
    {
        $query = Transaction::whereDate('created_at', Carbon::today());
        
        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        return [
            'total_sales' => $query->sum('total_amount'),
            'transaction_count' => $query->count(),
            'average_sale' => $query->avg('total_amount') ?? 0,
        ];
    }

    private function getWeeklyStats($storeId = null)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $query = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        
        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        $dailyTotals = $query->get()
            ->groupBy(function ($transaction) {
                return $transaction->created_at->format('Y-m-d');
            })
            ->map(function ($transactions) {
                return $transactions->sum('total_amount');
            });

        return $dailyTotals;
    }

    private function getLowStockItems($storeId = null)
    {
        $query = Inventory::where('quantity', '<=', 10)
            ->with(['product', 'store']);

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        return $query->take(5)->get();
    }
}