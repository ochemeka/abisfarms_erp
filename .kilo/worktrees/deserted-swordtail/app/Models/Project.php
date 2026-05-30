<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use BelongsToShop, SoftDeletes, LogsActivity;

    protected $fillable = [
        'shop_id',
        'created_by',
        'customer_id',
        'title',
        'description',
        'client_name',
        'client_phone',
        'client_email',
        'location',
        'budget',
        'total_boq',
        'amount_paid',
        'status',
        'start_date',
        'end_date',
        'actual_end_date',
        'completion_percent',
        'notes',
    ];

    protected $casts = [
        'budget'             => 'decimal:2',
        'total_boq'          => 'decimal:2',
        'amount_paid'        => 'decimal:2',
        'start_date'         => 'date',
        'end_date'           => 'date',
        'actual_end_date'    => 'date',
        'completion_percent' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(ProjectItem::class)
                    ->orderBy('sort_order');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getBalanceDueAttribute(): float
    {
        return $this->total_boq - $this->amount_paid;
    }

    public function recalculateTotal(): void
    {
        $total = $this->items()->sum('line_total');
        $this->update(['total_boq' => $total]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'completion_percent', 'total_boq'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}