<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowanceOption extends Model
{
    protected $fillable = [
        'name',
        'type',
        'amount',
        'min_amount',
        'max_amount',
        'created_by',
    ];
}
