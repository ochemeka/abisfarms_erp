<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sale extends Model
{
    use BelongsToShop, LogsActivity;

    protected $fillable = [
        'shop_id',
        'till_session_id',
        'served_by',
        'collected_by',
        'customer_id',
        'receipt_number',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'change_given',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'change_given'    => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────
    public function servedBy()
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function tillSession()
    {
        return $this->belongsTo(TillSession::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function kitchenOrder()
    {
        return $this->hasOne(KitchenOrder::class);
    }

    public function refundRequest()
    {
        return $this->hasOne(RefundRequest::class);
    }

    // ── Helpers ────────────────────────────────
    public function isRefundable(): bool
    {
        return $this->status === 'completed'
            && !$this->refundRequest()->exists();
    }

    public static function generateReceiptNumber(): string
    {
        $prefix = 'BH';
        $date   = now()->format('ymd');
        $count  = static::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // ── Activity log ───────────────────────────
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'total_amount', 'payment_method'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}