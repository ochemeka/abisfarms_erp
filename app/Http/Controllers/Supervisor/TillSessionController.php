<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\TillSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TillSessionController extends Controller
{
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

        return view('supervisor.till.index',
            compact('session', 'todaySessions'));
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
            ->log("Supervisor till opened with float ₦{$request->opening_float}");

        return redirect()
            ->route('supervisor.till.index')
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

        return view('supervisor.till.close',
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
            ->log("Supervisor till closed. Expected: ₦{$expected}, Actual: ₦{$actual}");

        $message = $status === 'flagged'
            ? "Till closed with discrepancy of ₦" . number_format(abs($discrepancy), 2) . "."
            : "Till closed successfully.";

        return redirect()
            ->route('supervisor.till.index')
            ->with($status === 'flagged' ? 'error' : 'success', $message);
    }

    public function reconcile(TillSession $tillSession): View
    {
        $tillSession->load(['cashier', 'sales', 'shop']);

        return view('supervisor.till.reconcile',
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
            ->log("Till session reconciled and approved by supervisor");

        return back()->with('success', 'Till session approved.');
    }
}
