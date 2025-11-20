<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaxSlab;

class SaturationDeduction extends Model
{
    protected $fillable = [
        'employee_id',
        'deduction_option',
        'title',
        'amount',
        'tax_slab_id',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function deductionOption()
    {
        return $this->hasOne('App\Models\DeductionOption', 'id', 'deduction_option');
    }

    public function taxSlab()
    {
        return $this->belongsTo(TaxSlab::class, 'tax_slab_id');
    }
    public static $saturationDeductiontype = [
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
