<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TillSession extends Model
{
    use BelongsToShop, LogsActivity;

    protected $fillable = [
        'shop_id',
        'user_id',
        'opening_float',
        'expected_cash',
        'actual_cash',
        'discrepancy',
        'status',
        'opened_at',
        'closed_at',
        'closed_by',
        'reconciled_by',
        'notes',
    ];

    protected $casts = [
        'opening_float' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'actual_cash'   => 'decimal:2',
        'discrepancy'   => 'decimal:2',
        'opened_at'     => 'datetime',
        'closed_at'     => 'datetime',
    ];

    // ── Relationships ──────────────────────────
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function reconciledBy()
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // ── Helpers ────────────────────────────────
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function hasDiscrepancy(): bool
    {
        return $this->discrepancy !== null && $this->discrepancy != 0;
    }

    // ── Activity log ───────────────────────────
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'actual_cash', 'discrepancy'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}