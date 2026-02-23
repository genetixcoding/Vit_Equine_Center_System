<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breeding extends Model
{
    use HasFactory;
    protected $table = 'breedings';
    public $timestamps = true;
    protected $fillable = [
    'femalehorse',
    'malehorse',
    'finance_id',
    'paid',
    'user_id',
    'stud',
    'horsename',
    'cost',
    'description',
    'status',
    ];
    public function femaleHorse ()
    {
        return $this->belongsTo(Horse::class, 'femalehorse', 'id');
    }
    public function maleHorse()
    {
        return $this->belongsTo(Horse::class, 'malehorse', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' ,'id');
    }
    public function finance()
    {
        return $this->belongsTo(Financial::class, 'finance_id' ,'id');
    }
    public function embryo()
    {
        return $this->hasMany(Embryo::class, 'breeding_id', 'id');
    }


}
