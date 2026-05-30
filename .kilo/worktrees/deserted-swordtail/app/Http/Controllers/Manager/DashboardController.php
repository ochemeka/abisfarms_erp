<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\TillSession;
use App\Models\RefundRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->shop_id;

        $pendingExpenses = Expense::where('status', 'pending')->count();

        $pendingRefunds = RefundRequest::where('status', 'pending')->count();

        $todayRevenue = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        $openTills = TillSession::where('shop_id', $shopId)
            ->where('status', 'open')
            ->count();

        return view('manager.dashboard', compact(
            'pendingExpenses',
            'pendingRefunds',
            'todayRevenue',
            'openTills'
        ));
    }
}