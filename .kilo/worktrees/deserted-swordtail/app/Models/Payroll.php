<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payroll extends Model
{
    use BelongsToShop, LogsActivity;

    protected $fillable = [
        'user_id',
        'shop_id',
        'processed_by',
        'month',
        'year',
        'days_worked',
        'days_absent',
        'days_late',
        'base_amount',
        'commission_amount',
        'bonus',
        'deductions',
        'gross_pay',
        'net_pay',
        'status',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'base_amount'       => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'bonus'             => 'decimal:2',
        'deductions'        => 'decimal:2',
        'gross_pay'         => 'decimal:2',
        'net_pay'           => 'decimal:2',
        'paid_at'           => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getMonthNameAttribute(): string
    {
        return \Carbon\Carbon::create($this->year, $this->month)
            ->format('F Y');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'net_pay'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}