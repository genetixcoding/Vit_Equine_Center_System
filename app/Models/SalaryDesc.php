<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryDesc extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'salary_descs';
    protected $fillable = [
        'salary_id',
        'amount',
    ];
    public function salary()
    {
        return $this->belongsTo(Salary::class, 'salary_id', 'id');
    }
}
