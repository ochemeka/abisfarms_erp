<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


 
class Invoice extends Model
{
    use BelongsToShop, SoftDeletes, LogsActivity;

    protected $fillable = [
        'shop_id',
        'created_by',
        'customer_id',
        'sale_id',
        'invoice_number',
        'type',
        'status',
        'client_name',
        'client_phone',
        'client_email',
        'client_address',
        'subtotal',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'notes',
        'terms',
        'issue_date',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate'        => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'issue_date'      => 'date',
        'due_date'        => 'date',
        'paid_at'         => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function getBalanceDueAttribute(): float
    {
        return $this->total_amount - $this->amount_paid;
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && !in_array($this->status, ['paid', 'cancelled']);
    }

    public static function generateNumber(int $shopId): string
    {
        $prefix = 'INV';
        $year   = now()->format('Y');
        $count  = static::where('shop_id', $shopId)
                        ->whereYear('created_at', $year)
                        ->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount_paid', 'total_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}