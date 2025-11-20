<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaxSlab;
use App\Models\AllowanceOption;
use App\Models\DeductionOption;

class PayslipType extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function taxSlabs()
    {
        return $this->hasMany(TaxSlab::class, 'payslip_type_id');
    }

    public function allowanceOptions()
    {
        return $this->belongsToMany(AllowanceOption::class, 'payslip_type_allowance_option', 'payslip_type_id', 'allowance_option_id');
    }

    public function deductionOptions()
    {
        return $this->belongsToMany(DeductionOption::class, 'payslip_type_deduction_option', 'payslip_type_id', 'deduction_option_id');
    }
}
