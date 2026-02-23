<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'visits';
    protected $fillable = [
        'user_id',
        'stud_id',
        'paid',
        'visitprice',
        'discount',
    ];


    public function visitdescs()
{
    return $this->hasMany(Visitdesc::class, 'visit_id');
}
    public function stud()
    {
        return $this->belongsTo(Stud::class, 'stud_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
