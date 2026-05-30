<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyBatchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id', 'shop_id', 'animal_type', 'quantity',
        'unit_cost', 'processing_cost_per_head', 'total_cost',
        'cost_allocation_per_unit', 'notes',
    ];

    protected $casts = [
        'quantity'                  => 'integer',
        'unit_cost'                 => 'decimal:2',
        'processing_cost_per_head'  => 'decimal:2',
        'total_cost'                => 'decimal:2',
        'cost_allocation_per_unit'  => 'decimal:2',
    ];

    public function batch() { return $this->belongsTo(SupplyBatch::class, 'batch_id'); }
    public function shop()  { return $this->belongsTo(Shop::class); }
}
