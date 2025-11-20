<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeIdentification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'id_type',
        'id_number',
        'created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
