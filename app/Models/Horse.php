<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horse extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'horses';
    protected $fillable = [
        'name',
        'image',
        'shelter',
        'gender',
        'status',
        'description',
        // 'age',
        // 'birth_date',
    ];

    public function stud(){
        return $this->belongsTo(Stud::class, 'stud_id', 'id');
    }
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }
    public function femaleHorse():HasMany
    {
        return $this->hasMany(Breeding::class, 'femalehorse', 'id');
    }
    public function maleHorse():HasMany
    {
        return $this->hasMany(Breeding::class, 'malehorse', 'id');
    }

    public function visitdesc():HasMany
    {
        return $this->hasMany(VisitDesc::class, 'horse_id', 'id');
    }
    public function treatment():HasMany
    {
        return $this->hasMany(Treatment::class, 'horse_id', 'id');
    }
    public function taskdesc():HasMany
    {
        return $this->hasMany(TaskDesc::class, 'horse_id', 'id');
    }
    public function feedingdesc():HasMany
    {
        return $this->hasMany(FeedingDesc::class, 'horse_id', 'id');
    }
    public function beddingdesc():HasMany
    {
        return $this->hasMany(BeddingDesc::class, 'horse_id', 'id');
    }

}
