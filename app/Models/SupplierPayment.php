<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'batch_id', 'shop_id', 'amount',
        'payment_method', 'reference', 'payment_date', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function supplier()  { return $this->belongsTo(Supplier::class); }
    public function batch()     { return $this->belongsTo(SupplyBatch::class); }
    public function shop()      { return $this->belongsTo(Shop::class); }
    public function recordedBy(){ return $this->belongsTo(User::class, 'recorded_by'); }
}
