<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'color',
        'accepts_orders',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'accepts_orders' => 'boolean',
        'is_active'      => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function staff()
    {
        return $this->hasMany(StaffProfile::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'department_products');
    }
}