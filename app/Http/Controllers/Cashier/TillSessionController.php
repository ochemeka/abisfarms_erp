<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\TillSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TillSessionController extends Controller
{
    // Show open till page or current session
    public function index(): View
    {
        $session = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->with('sales')
            ->first();

        $todaySessions = TillSession::where('user_id', auth()->id())
            ->whereDate('opened_at', today())
            ->latest()
            ->get();

        return view('cashier.till.index',
            compact('session', 'todaySessions'));
    }

    // Open a new till session
    public function open(Request $request): RedirectResponse
    {
        // Check no existing open session
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
            ->log("Till opened with float ₦{$request->opening_float}");

        return redirect()
            ->route('cashier.till.index')
            ->with('success', 'Till opened successfully. You can now process sales.');
    }

    // Show close till form
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

        return view('cashier.till.close',
            compact('session', 'totalSales'));
    }

    // Submit actual cash count — blind close
    public function close(Request $request): RedirectResponse
    {
        $session = TillSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->firstOrFail();

        $request->validate([
            'actual_cash' => ['required', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        // Calculate expected cash
        $cashSales = $session->sales()
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('total_amount');

        $expected    = $session->opening_float + $cashSales;
        $actual      = $request->actual_cash;
        $discrepancy = $actual - $expected;

        $status = abs($discrepancy) > 0 ? 'flagged' : 'closed';

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
            ->log("Till closed. Expected: ₦{$expected}, Actual: ₦{$actual}, Discrepancy: ₦{$discrepancy}");

        $message = $status === 'flagged'
            ? "Till closed with discrepancy of ₦" . number_format(abs($discrepancy), 2) . ". Supervisor notified."
            : "Till closed successfully. No discrepancy.";

        return redirect()
            ->route('cashier.till.index')
            ->with($status === 'flagged' ? 'error' : 'success', $message);
    }

    // Supervisor reconcile view
    public function reconcile(TillSession $tillSession): View
    {
        $tillSession->load(['cashier', 'sales', 'shop']);

        return view('cashier.till.reconcile',
            compact('tillSession'));
    }

    // Supervisor signs off
    public function approve(TillSession $tillSession): RedirectResponse
    {
        abort_unless(
            auth()->user()->hasAnyRole(['supervisor', 'manager', 'owner']),
            403
        );

        $tillSession->update([
            'status'         => 'closed',
            'reconciled_by'  => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($tillSession)
            ->log("Till session reconciled and approved");

        return back()->with('success', 'Till session approved.');
    }
}