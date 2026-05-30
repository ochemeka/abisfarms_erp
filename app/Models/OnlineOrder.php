<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OnlineOrder extends Model
{
    use BelongsToShop, LogsActivity;

    protected $fillable = [
        'shop_id',
        'customer_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'items',
        'confirmed_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'items'        => 'array',
        'confirmed_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public static function generateNumber(int $shopId): string
    {
        $prefix = 'ORD';
        $date   = now()->format('ymd');
        $count  = static::where('shop_id', $shopId)
                        ->whereDate('created_at', today())
                        ->count() + 1;
        return $prefix . $date . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}