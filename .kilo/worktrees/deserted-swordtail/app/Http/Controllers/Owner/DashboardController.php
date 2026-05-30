<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Product;
use App\Models\User;
use App\Models\Expense;
use App\Models\TillSession;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user   = auth()->user();
        $shopId = session('active_shop_id') ?? $user->shop_id;

        // Revenue today — all shops or active shop
        $revenueToday = Sale::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // Revenue this month
        $revenueMonth = Sale::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Sales today count
        $salesToday = Sale::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->whereDate('created_at', today())
            ->count();

        // Active shops
        $totalShops = Shop::where('is_active', true)->count();

        // Active staff
        $totalStaff = User::when($shopId,
                fn($q) => $q->where('shop_id', $shopId))
            ->where('is_active', true)
            ->count();

        // Low stock alerts
        $lowStock = Product::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->where('track_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->count();

        // Pending expenses
        $pendingExpenses = Expense::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->where('status', 'pending')
            ->count();

        // Open tills
        $openTills = TillSession::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->where('status', 'open')
            ->count();

        // Revenue by shop (for chart)
        $shopRevenues = Shop::withCount(['users'])
            ->get()
            ->map(function ($shop) {
                $shop->revenue_today = Sale::withoutGlobalScope('shop')
                    ->where('shop_id', $shop->id)
                    ->whereDate('created_at', today())
                    ->where('status', 'completed')
                    ->sum('total_amount');
                return $shop;
            });

        // Recent sales
        $recentSales = Sale::withoutGlobalScope('shop')
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
            ->with(['servedBy', 'shop'])
            ->latest()
            ->take(8)
            ->get();

        // Last 7 days revenue chart data
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Sale::withoutGlobalScope('shop')
                ->when($shopId, fn($q) => $q->where('shop_id', $shopId))
                ->whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
            $last7Days->push([
                'date'    => $date->format('D'),
                'revenue' => (float) $revenue,
            ]);
        }

        return view('owner.dashboard', compact(
            'revenueToday',
            'revenueMonth',
            'salesToday',
            'totalShops',
            'totalStaff',
            'lowStock',
            'pendingExpenses',
            'openTills',
            'shopRevenues',
            'recentSales',
            'last7Days'
        ));
    }
}