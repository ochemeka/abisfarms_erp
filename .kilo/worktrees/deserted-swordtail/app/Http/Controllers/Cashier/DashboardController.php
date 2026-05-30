<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\TillSession;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tillSession = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        $recentSales = Sale::where('served_by', auth()->id())
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->with('items')
            ->latest()
            ->take(10)
            ->get();

        $todayTotal = $recentSales->sum('total_amount');
        $pendingRefunds = \App\Models\RefundRequest::where('requested_by', auth()->id())
            ->where('status', 'pending')
            ->count();

        return view('cashier.dashboard', compact(
            'tillSession',
            'recentSales',
            'todayTotal',
            'pendingRefunds'
        ));
    }
}