<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;

    const TYPES = [
        'restaurant'   => 'Restaurant',
        'pharmacy'     => 'Pharmacy',
        'salon'        => 'Salon / Barbershop',
        'supermarket'  => 'Supermarket',
        'market'       => 'Village Market',
        'butchery'     => 'Butchery',
        'construction' => 'Construction',
        'clinic'       => 'Clinic',
        'hotel'        => 'Hotel',
        'laundry'      => 'Laundry',
        'hybrid'       => 'Hybrid / Other',
    ];

    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'phone',
        'email',
        'is_active',
        'manager_id',
        'settings',
        'logo_path',
        'tagline',
        'address_full',
        'bank_name',
        'bank_account',
        'bank_account_name',
        'invoice_prefix',
        'invoice_footer',
        'default_tax_rate',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings'  => 'array',
    ];

    // ── Relationships ──────────────────────────
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // ── Helpers ────────────────────────────────
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'restaurant'   => 'red',
            'pharmacy'     => 'blue',
            'salon'        => 'purple',
            'supermarket'  => 'green',
            'market'       => 'teal',
            'butchery'     => 'orange',
            'construction' => 'amber',
            'clinic'       => 'sky',
            'hotel'        => 'indigo',
            'laundry'      => 'cyan',
            default        => 'gray',
        };
    }

    // Feature flags based on shop type
    public function usesTables(): bool
    {
        return in_array($this->type, [
            'restaurant', 'hotel', 'clinic'
        ]);
    }

    public function usesDepartments(): bool
    {
        return in_array($this->type, [
            'restaurant', 'hotel', 'clinic',
            'pharmacy', 'salon', 'hybrid', 'supermarket'
        ]);
    }
    public function getLogoUrlAttribute(): string
{
    if ($this->logo_path && file_exists(public_path($this->logo_path))) {
        return asset($this->logo_path);
    }
    return '';
}

    public function usesProjects(): bool
    {
        return in_array($this->type, [
            'construction', 'hybrid'
        ]);
    }

    public function usesPrescriptions(): bool
    {
        return in_array($this->type, [
            'pharmacy', 'clinic'
        ]);
    }
}