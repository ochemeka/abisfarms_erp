<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\TillSession;
use App\Models\RefundRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->shop_id;

        $pendingRefunds = RefundRequest::where('status', 'pending')->count();

        $todayRevenue = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        $todayTransactions = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->count();

        $openTills = TillSession::where('shop_id', $shopId)
            ->where('status', 'open')
            ->count();

        return view('supervisor.dashboard', compact(
            'pendingRefunds',
            'todayRevenue',
            'todayTransactions',
            'openTills'
        ));
    }
}