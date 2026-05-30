<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'clock_in',
        'clock_out',
        'hours_worked',
        'status',
        'note',
        'recorded_by',
    ];

    protected $casts = [
        'date'         => 'date',
        'clock_in'     => 'datetime',
        'clock_out'    => 'datetime',
        'hours_worked' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function isLate(): bool
    {
        return $this->status === 'late';
    }
}