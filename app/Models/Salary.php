<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'salaries';
    protected $fillable = [
        'finance_id',
        'user_id',
        'salaryamount',
    ];
    public function finance():BelongsTo
    {
        return $this->belongsTo(Financial::class, 'finance_id', 'id');
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function salarydesc():HasMany
    {
        return $this->hasMany(SalaryDesc::class, 'salary_id', 'id');
    }
}
