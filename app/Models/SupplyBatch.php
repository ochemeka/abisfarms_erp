<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id', 'supplier_id', 'batch_code', 'batch_label', 'batch_date',
        'status', 'total_cost', 'amount_paid', 'balance_due', 'payment_status',
        'payment_due_date', 'notes', 'received_by', 'activated_at',
    ];

    protected $casts = [
        'batch_date'       => 'date',
        'payment_due_date' => 'date',
        'activated_at'     => 'datetime',
        'total_cost'       => 'decimal:2',
        'amount_paid'      => 'decimal:2',
        'balance_due'      => 'decimal:2',
    ];

    public function shop()     { return $this->belongsTo(Shop::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function items()    { return $this->hasMany(SupplyBatchItem::class, 'batch_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }

    public function scopeForShop($query, $shopId) { return $query->where('shop_id', $shopId); }
}
