<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\Shop;




class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'shop_id',
        'is_active',
        'scope',
        'last_login_at',
    ];

    // Get the currently active shop for this user
public function getActiveShop(): ?Shop
{
    $shopId = session('active_shop_id') ?? $this->shop_id;
    if (!$shopId) return null;
    return Shop::find($shopId);
}

// Check if owner is currently viewing a specific shop context
public function isViewingShop(): bool
{
    return session()->has('active_shop_id');
}

// Get all shops this user can access
public function accessibleShops()
{
    if ($this->scope === 'all' || $this->hasRole(['owner', 'site-admin'])) {
        return Shop::where('is_active', true)->get();
    }
    if ($this->scope === 'regional') {
        return Shop::where('is_active', true)
            ->where('manager_id', $this->id)
            ->orWhere('id', $this->shop_id)
            ->get();
    }
    return Shop::where('id', $this->shop_id)->get();
}

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // ── Helpers ────────────────────────────────────
    public function canAccessShop(int $shopId): bool
    {
        if ($this->scope === 'all') return true;
        if ($this->scope === 'regional') {
            return $this->shop_id === $shopId;
        }
        return $this->shop_id === $shopId;
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    // ── Activity Log ───────────────────────────────
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'shop_id', 'is_active', 'scope'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}