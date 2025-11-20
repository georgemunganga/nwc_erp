<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaxSlab;

class DeductionOption extends Model
{
    protected $fillable = [
        'name',
        'type',
        'amount',
        'min_amount',
        'max_amount',
        'created_by',
    ];

    public function taxSlabs()
    {
        return $this->belongsToMany(TaxSlab::class, 'deduction_option_tax_slab');
    }
}
