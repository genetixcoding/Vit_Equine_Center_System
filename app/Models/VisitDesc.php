<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitDesc extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'visit_descs';
    protected $fillable = [
    'visit_id',
    'horse_id',
    'case',
    'image',
    'treatment',
    'description',
    'caseprice'
];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }
    public function horse()
    {
        return $this->belongsTo(Horse::class, 'horse_id');
    }
}
