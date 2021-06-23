<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    const MS = 42500;   //Minimal Salary
    const MCI = 2917;   // Monthly Calculation Index
    const CPC = 0.1;    // Compulsory pension contributions сoefficient
    const CSHI = 0.02;  // Compulsory social health insurance сoefficient
    const CSHCCI = 0.02;//Compulsory social health care contributions Insurance coefficient
    const SSC = 0.035;//Social Security contributions coefficient

    protected $fillable = [
        'salary_id',
        'iit',
        'ssc',
        'cshi',
        'cpc',
        'cshcci'
    ];

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}
