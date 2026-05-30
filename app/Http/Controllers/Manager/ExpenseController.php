<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::with(['recordedBy', 'approvedBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_month' => Expense::whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->where('status', 'approved')
                ->sum('amount'),
            'pending'     => Expense::where('status', 'pending')->count(),
            'count_month' => Expense::whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->count(),
        ];

        $monthly = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->where('status', 'approved')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('manager.expenses.index',
            compact('expenses', 'stats', 'monthly'));
    }

    public function show(Expense $expense): View
    {
        $expense->load(['recordedBy', 'approvedBy']);
        return view('manager.expenses.show', compact('expense'));
    }

    public function approve(Expense $expense): RedirectResponse
    {
        abort_if(! $expense->isPending(), 422, 'This expense is not pending.');

        $expense->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($expense)
            ->log("Expense approved: {$expense->title} — ₦{$expense->amount}");

        return back()->with('success',
            'Expense approved: ₦' . number_format($expense->amount, 2));
    }

    public function reject(Request $request, Expense $expense): RedirectResponse
    {
        abort_if(! $expense->isPending(), 422, 'This expense is not pending.');

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ]);

        $expense->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes'       => ($expense->notes ? $expense->notes . "\n" : '')
                             . 'Rejected: ' . $request->rejection_reason,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($expense)
            ->log("Expense rejected: {$expense->title} — ₦{$expense->amount}");

        return back()->with('success', 'Expense rejected.');
    }
}