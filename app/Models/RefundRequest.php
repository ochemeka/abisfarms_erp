<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RefundRequest extends Model
{
    use BelongsToShop, LogsActivity;

    protected $fillable = [
        'sale_id',
        'shop_id',
        'requested_by',
        'approved_by',
        'amount',
        'reason',
        'status',
        'rejection_reason',
        'resolved_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Helpers ────────────────────────────────
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSmallRefund(): bool
    {
        return $this->amount <= 5000;
    }

    // ── Activity log ───────────────────────────
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount', 'approved_by'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}