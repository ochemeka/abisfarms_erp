<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToShop
{
    protected static function bootBelongsToShop(): void
    {
        static::addGlobalScope('shop', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();

                // Owner and site-admin bypass scope
                // unless they have set an active shop context
                if ($user->hasRole(['owner', 'site-admin'])) {
                    $shopId = session('active_shop_id');
                    if ($shopId) {
                        $builder->where(
                            (new static)->getTable() . '.shop_id',
                            $shopId
                        );
                    }
                    // No session = see everything (overview mode)
                    return;
                }

                // All other roles — scope to their shop
                $shopId = session('active_shop_id')
                    ?? $user->shop_id;

                if ($user->scope === 'branch' && $shopId) {
                    $builder->where(
                        (new static)->getTable() . '.shop_id',
                        $shopId
                    );
                }
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && empty($model->shop_id)) {
                $model->shop_id = session('active_shop_id')
                    ?? auth()->user()->shop_id;
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop::class);
    }

    public static function allShops(): Builder
    {
        return static::withoutGlobalScope('shop');
    }
}