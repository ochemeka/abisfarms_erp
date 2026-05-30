<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    // All pending refunds for this shop
    public function index(): View
    {
        $pending = RefundRequest::where('status', 'pending')
            ->with(['sale.items.product', 'requestedBy'])
            ->latest()
            ->get();

        $resolved = RefundRequest::whereIn('status', ['approved', 'rejected'])
            ->with(['sale', 'requestedBy', 'approvedBy'])
            ->latest()
            ->take(20)
            ->get();

        return view('supervisor.refunds.index',
            compact('pending', 'resolved'));
    }

    // Approve a refund request
    public function approve(
        Request $request,
        RefundRequest $refundRequest
    ): RedirectResponse {

        abort_unless($refundRequest->status === 'pending', 403);

        $isSmall = $refundRequest->amount <= 5000;

        // Supervisor can only approve small refunds
        if (!$isSmall && !auth()->user()->hasAnyRole(['manager', 'owner'])) {
            return back()->with('error',
                'Refunds above ₦5,000 require Manager approval.');
        }

        DB::transaction(function () use ($refundRequest) {
            // Mark sale as refunded
            $refundRequest->sale->update(['status' => 'refunded']);

            // Restore stock for each item
            foreach ($refundRequest->sale->items as $item) {
                $product = $item->product;

                if ($product && $product->track_stock) {
                    $before = $product->stock_quantity;
                    $after  = $before + $item->quantity;

                    $product->increment('stock_quantity', $item->quantity);

                    StockMovement::create([
                        'shop_id'         => auth()->user()->shop_id,
                        'product_id'      => $product->id,
                        'user_id'         => auth()->id(),
                        'sale_id'         => $refundRequest->sale_id,
                        'type'            => 'return',
                        'quantity_before' => $before,
                        'quantity_change' => $item->quantity,
                        'quantity_after'  => $after,
                        'note'            => "Refund approved — Sale #{$refundRequest->sale->receipt_number}",
                    ]);
                }
            }

            // Mark refund as approved
            $refundRequest->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'resolved_at' => now(),
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($refundRequest)
                ->log("Refund approved — ₦{$refundRequest->amount} for Sale #{$refundRequest->sale->receipt_number}");
        });

        return back()->with('success',
            "Refund of ₦" . number_format($refundRequest->amount, 2)
            . " approved. Stock restored.");
    }

    // Reject a refund request
    public function reject(
        Request $request,
        RefundRequest $refundRequest
    ): RedirectResponse {

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ]);

        abort_unless($refundRequest->status === 'pending', 403);

        $refundRequest->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
            'resolved_at'      => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($refundRequest)
            ->log("Refund rejected — Sale #{$refundRequest->sale->receipt_number}. Reason: {$request->rejection_reason}");

        return back()->with('success', 'Refund request rejected.');
    }
}