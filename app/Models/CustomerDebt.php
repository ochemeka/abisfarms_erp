<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CustomerDebt extends Model
{
    use BelongsToShop, LogsActivity;

    protected $fillable = [
        'shop_id',
        'customer_id',
        'sale_id',
        'recorded_by',
        'amount_owed',
        'amount_paid',
        'due_date',
        'status',
        'notes',
        'settled_at',
    ];

    protected $casts = [
        'amount_owed' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date'    => 'date',
        'settled_at'  => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getBalanceAttribute(): float
    {
        return $this->amount_owed - $this->amount_paid;
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== 'settled';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount_paid'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}