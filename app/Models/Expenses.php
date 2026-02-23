<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'expenses';
    protected $fillable = [
        'finance_id',
        'item',
        'cost',
    ];
    public function finance()
    {
        return $this->belongsTo(Financial::class, 'finance_id');
    }
}
