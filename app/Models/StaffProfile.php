<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'user_id',
        'shop_id',
        'department_id',
        'employee_id',
        'job_title',
        'pay_type',
        'base_salary',
        'daily_rate',
        'commission_rate',
        'hire_date',
        'end_date',
        'bank_name',
        'account_number',
        'account_name',
        'next_of_kin',
        'next_of_kin_phone',
        'notes',
    ];

    protected $casts = [
        'base_salary'     => 'decimal:2',
        'daily_rate'      => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'hire_date'       => 'date',
        'end_date'        => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'user_id', 'user_id');
    }
}