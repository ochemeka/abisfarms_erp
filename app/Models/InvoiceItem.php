<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'discount',
        'line_total',
        'sort_order',
    ];

    protected $casts = [
        'quantity'   => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discount'   => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // e.g. "1.500 kg × ₦2,000.00 = ₦3,000.00"
    public function getFormattedLineAttribute(): string
    {
        return number_format($this->quantity, 3) . ' '
            . $this->unit . ' × ₦'
            . number_format($this->unit_price, 2)
            . ' = ₦' . number_format($this->line_total, 2);
    }
}