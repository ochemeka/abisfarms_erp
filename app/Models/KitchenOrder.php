<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;

class KitchenOrder extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'sale_id',
        'shop_id',
        'taken_by',
        'table_number',
        'customer_name',
        'status',
        'notes',
        'fired_at',
        'ready_at',
        'dispatched_at',
    ];

    protected $casts = [
        'fired_at'      => 'datetime',
        'ready_at'      => 'datetime',
        'dispatched_at' => 'datetime',
        'table_number'  => 'integer',
    ];

    // ── Relationships ──────────────────────────
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }

    // ── Helpers ────────────────────────────────
    public function isDelayed(): bool
    {
        if ($this->status !== 'cooking') return false;
        return now()->diffInMinutes($this->fired_at) > 20;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'orange',
            'cooking'    => 'blue',
            'ready'      => 'green',
            'dispatched' => 'gray',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }
}