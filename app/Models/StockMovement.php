<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'shop_id',
        'product_id',
        'user_id',
        'sale_id',
        'type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'note',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_change' => 'integer',
        'quantity_after'  => 'integer',
    ];

    // ── Relationships ──────────────────────────
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // ── Helpers ────────────────────────────────
    public function isDeduction(): bool
    {
        return $this->quantity_change < 0;
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'sale'         => 'red',
            'purchase'     => 'green',
            'adjustment'   => 'orange',
            'transfer_in'  => 'blue',
            'transfer_out' => 'purple',
            'waste'        => 'gray',
            'return'       => 'teal',
            default        => 'gray',
        };
    }
}