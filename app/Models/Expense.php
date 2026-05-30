<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'shop_id',
        'recorded_by',
        'approved_by',
        'category',
        'title',
        'amount',
        'expense_date',
        'vendor',
        'receipt_path',
        'notes',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'approved_at'  => 'datetime',
        'expense_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public static function categories(): array
    {
        return [
            'supplies'    => 'Supplies & Materials',
            'utilities'   => 'Utilities',
            'maintenance' => 'Maintenance & Repairs',
            'transport'   => 'Transport & Logistics',
            'staff'       => 'Staff & Labour',
            'marketing'   => 'Marketing & Advertising',
            'equipment'   => 'Equipment',
            'rent'        => 'Rent & Lease',
            'other'       => 'Other',
        ];
    }
}