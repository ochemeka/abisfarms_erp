<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RefundController extends Controller
{
    // List cashier's own refund requests
    public function index(): View
    {
        $refunds = RefundRequest::where('requested_by', auth()->id())
            ->with(['sale', 'approvedBy'])
            ->latest()
            ->paginate(15);

        return view('cashier.refunds.index', compact('refunds'));
    }

    // Show form to request refund for a sale
    public function create(Sale $sale): View
    {
        abort_unless(
            $sale->status === 'completed' && !$sale->refundRequest,
            403,
            'This sale cannot be refunded.'
        );

        $sale->load('items.product');

        return view('cashier.refunds.create', compact('sale'));
    }

    // Submit refund request
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'amount'  => ['required', 'numeric', 'min:0.01'],
            'reason'  => ['required', 'string', 'max:500'],
        ]);

        $sale = Sale::findOrFail($validated['sale_id']);

        // Prevent duplicate refund requests
        if ($sale->refundRequest) {
            return back()->with('error',
                'A refund request already exists for this sale.');
        }

        // Amount cannot exceed sale total
        if ($validated['amount'] > $sale->total_amount) {
            return back()->with('error',
                'Refund amount cannot exceed the sale total of ₦'
                . number_format($sale->total_amount, 2));
        }

        RefundRequest::create([
            'sale_id'      => $sale->id,
            'shop_id'      => auth()->user()->shop_id,
            'requested_by' => auth()->id(),
            'amount'       => $validated['amount'],
            'reason'       => $validated['reason'],
            'status'       => 'pending',
        ]);

        activity()
            ->causedBy(auth()->user())
            ->log("Refund requested for Sale #{$sale->receipt_number} — ₦{$validated['amount']}");

        return redirect()
            ->route('cashier.refunds.index')
            ->with('success',
                'Refund request submitted. Awaiting supervisor approval.');
    }
}