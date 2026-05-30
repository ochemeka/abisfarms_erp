<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id', 'name', 'code', 'phone', 'phone2', 'email', 'address',
        'payment_terms', 'credit_days', 'bank_name', 'bank_account',
        'bank_account_name', 'total_supplied', 'total_paid', 'is_active', 'notes',
    ];

    protected $casts = [
        'total_supplied' => 'decimal:2',
        'total_paid'     => 'decimal:2',
        'credit_days'    => 'integer',
        'is_active'      => 'boolean',
    ];

    public function shop()       { return $this->belongsTo(Shop::class); }
    public function batches()    { return $this->hasMany(SupplyBatch::class); }
    public function payments()   { return $this->hasMany(SupplierPayment::class); }

    public function getBalanceOwedAttribute(): float
    {
        return (float) $this->total_supplied - (float) $this->total_paid;
    }

    public function scopeActive($query)         { return $query->where('is_active', true); }
    public function scopeForShop($query, $shopId) { return $query->where('shop_id', $shopId); }
}
