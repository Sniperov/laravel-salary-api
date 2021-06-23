<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary',
        'norma', 
        'count_worked_days', 
        'tax_ms', 
        'year', 
        'mouth', 
        'is_pensioner', 
        'is_invalid', 
        'invalid_group',
    ];

    public function tax()
    {
        return $this->hasOne(Tax::class);
    }
}
