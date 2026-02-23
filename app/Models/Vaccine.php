<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'horse_id',
        'description',
        'image',
    ];

    // علاقة إلى الحصان
    public function horse()
    {
        return $this->belongsTo(Horse::class, 'horse_id');
    }
}
