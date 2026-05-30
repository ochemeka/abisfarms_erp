<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TillSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TillSessionController extends Controller
{
    // Manager sees ALL till sessions for their shop (not just their own)
    public function index(): View
    {
        $shopId = auth()->user()->shop_id;

        // Manager's own open session if any
        $mySession = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->with('sales')
            ->first();

        // All shop sessions today — managers oversee everyone
        $todaySessions = TillSession::where('shop_id', $shopId)
            ->whereDate('opened_at', today())
            ->with(['sales', 'user'])
            ->latest()
            ->get();

        // Flagged sessions needing approval
        $flaggedSessions = TillSession::where('shop_id', $shopId)
            ->where('status', 'flagged')
            ->with(['user'])
            ->latest()
            ->get();

        return view('manager.till.index',
            compact('mySession', 'todaySessions', 'flaggedSessions'));
    }

    public function open(Request $request): RedirectResponse
    {
        $existing = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return back()->with('error',
                'You already have an open till session.');
        }

        $request->validate([
            'opening_float' => ['required', 'numeric', 'min:0'],
        ]);

        $session = TillSession::create([
            'shop_id'       => auth()->user()->shop_id,
            'user_id'       => auth()->id(),
            'opening_float' => $request->opening_float,
            'expected_cash' => $request->opening_float,
            'status'        => 'open',
            'opened_at'     => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($session)
            ->log("Manager till opened with float ₦{$request->opening_float}");

        return redirect()
            ->route('manager.till.index')
            ->with('success', 'Till opened successfully.');
    }

    public function closeForm(): View
    {
        $session = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->with('sales')
            ->firstOrFail();

        $totalSales = $session->sales()
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('total_amount');

        return view('manager.till.close',
            compact('session', 'totalSales'));
    }

    public function close(Request $request): RedirectResponse
    {
        $session = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->firstOrFail();

        $request->validate([
            'actual_cash' => ['required', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $cashSales   = $session->sales()
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('total_amount');

        $expected    = $session->opening_float + $cashSales;
        $actual      = $request->actual_cash;
        $discrepancy = $actual - $expected;
        $status      = abs($discrepancy) > 0 ? 'flagged' : 'closed';

        $session->update([
            'expected_cash' => $expected,
            'actual_cash'   => $actual,
            'discrepancy'   => $discrepancy,
            'status'        => $status,
            'closed_at'     => now(),
            'closed_by'     => auth()->id(),
            'notes'         => $request->notes,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($session)
            ->log("Manager till closed. Expected: ₦{$expected}, Actual: ₦{$actual}");

        $message = $status === 'flagged'
            ? "Till closed with discrepancy of ₦" . number_format(abs($discrepancy), 2) . "."
            : "Till closed successfully.";

        return redirect()
            ->route('manager.till.index')
            ->with($status === 'flagged' ? 'error' : 'success', $message);
    }

    // Manager can reconcile ANY flagged session in their shop
    public function reconcile(TillSession $tillSession): View
    {
        $tillSession->load(['user', 'sales', 'shop']);

        return view('manager.till.reconcile',
            compact('tillSession'));
    }

    public function approve(TillSession $tillSession): RedirectResponse
    {
        $tillSession->update([
            'status'        => 'closed',
            'reconciled_by' => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($tillSession)
            ->log("Till session reconciled and approved by manager");

        return back()->with('success', 'Till session approved and closed.');
    }
}
