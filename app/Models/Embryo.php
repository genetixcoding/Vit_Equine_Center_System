<?php

namespace App\Models;

use Brick\Math\BigInteger;

/**
 * @property BigInteger $cost
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Embryo extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'embryos';
    protected $fillable = [
        'user_id',
        'finance_id',
        'breeding_id',
        'description',
        'localhorsename',
        'cost',
        'paid',
        'status'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function breeding():BelongsTo
    {
        return $this->belongsTo(Breeding::class, 'breeding_id', 'id');
    }
    public function finance() : BelongsTo
    {
        return $this->belongsTo(Financial::class, 'finance_id');
    }
}
