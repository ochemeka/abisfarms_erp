<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use BelongsToShop, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'email',
        'address',
        'loyalty_points',
        'total_spent',
        'total_debt',
        'date_of_birth',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'loyalty_points' => 'decimal:2',
        'total_spent'    => 'decimal:2',
        'total_debt'     => 'decimal:2',
        'date_of_birth'  => 'date',
        'is_active'      => 'boolean',
    ];

    public function debts()
    {
        return $this->hasMany(CustomerDebt::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function outstandingDebt(): float
    {
        return $this->debts()
            ->whereIn('status', ['outstanding', 'partial'])
            ->sum(\Illuminate\Support\Facades\DB::raw('amount_owed - amount_paid'));
    }
}