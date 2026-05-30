<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectItem extends Model
{
    protected $fillable = [
        'project_id',
        'description',
        'unit',
        'quantity',
        'unit_price',
        'line_total',
        'status',
        'category',
        'sort_order',
        'notes',
    ];

    protected $casts = [
        'quantity'   => 'decimal:3',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Auto-calculate line total before saving
    protected static function booted(): void
    {
        static::saving(function ($item) {
            $item->line_total = $item->quantity * $item->unit_price;
        });
    }
}