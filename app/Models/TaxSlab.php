<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;
use App\Models\User;

class TaxSlab extends Model
{
    protected $fillable = [
        'name',
        'min_salary',
        'max_salary',
        'rate',
        'created_by',
        'payslip_type_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payslipType()
    {
        return $this->belongsTo(PayslipType::class, 'payslip_type_id');
    }
}
