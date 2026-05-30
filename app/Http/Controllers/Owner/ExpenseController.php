<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['submittedBy', 'approvedBy'])
            ->latest('expense_date');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        $expenses   = $query->paginate(20)->withQueryString();
        $categories = Expense::categories();

        // Summary totals for current filter
        $totals = [
            'pending'  => Expense::pending()->sum('amount'),
            'approved' => Expense::approved()->sum('amount'),
            'rejected' => Expense::rejected()->sum('amount'),
        ];

        return view('owner.expenses.index', compact('expenses', 'categories', 'totals'));
    }

    public function create()
    {
        $categories = Expense::categories();
        return view('owner.expenses.create', compact('categories'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $data = $request->validated();

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('expenses/receipts', 'public');
        }

        Expense::create([
            'shop_id'      => auth()->user()->shop_id,
            'submitted_by' => auth()->id(),
            'category'     => $data['category'],
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'amount'       => $data['amount'],
            'receipt_path' => $receiptPath,
            'status'       => 'pending',
            'expense_date' => $data['expense_date'],
        ]);

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Expense recorded and pending approval.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['submittedBy', 'approvedBy']);
        return view('owner.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        // Only pending expenses can be edited
        abort_if(! $expense->isPending(), 403, 'Only pending expenses can be edited.');

        $categories = Expense::categories();
        return view('owner.expenses.edit', compact('expense', 'categories'));
    }

    public function update(StoreExpenseRequest $request, Expense $expense)
    {
        abort_if(! $expense->isPending(), 403, 'Only pending expenses can be edited.');

        $data = $request->validated();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $data['receipt_path'] = $request->file('receipt')->store('expenses/receipts', 'public');
        }

        $expense->update([
            'category'     => $data['category'],
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'amount'       => $data['amount'],
            'expense_date' => $data['expense_date'],
            'receipt_path' => $data['receipt_path'] ?? $expense->receipt_path,
        ]);

        return redirect()->route('owner.expenses.show', $expense)
            ->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        abort_if($expense->isApproved(), 403, 'Approved expenses cannot be deleted.');

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Expense deleted.');
    }

    public function approve(Expense $expense)
    {
        abort_if(! $expense->isPending(), 422, 'This expense is not pending.');

        $expense->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Expense approved.');
    }

    public function reject(Request $request, Expense $expense)
    {
        abort_if(! $expense->isPending(), 422, 'This expense is not pending.');

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $expense->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'rejected_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Expense rejected.');
    }
}
