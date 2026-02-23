<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;
    protected $table = 'treatments';

    protected $fillable = [
        'user_id',
        'horse_id',
        'embryo_id',
    ];
    public $timestamps = true;

    // Define the relationship with TreatmentDesc
    public function treatmentdesc()
    {
        return $this->hasMany(TreatmentDesc::class, 'treatment_id');
    }

    // Define the relationship with the Horse model
    public function horse()
    {
        return $this->belongsTo(Horse::class, 'horse_id', 'id'); // Ensure foreign and local keys match the schema
    }
    // Define the relationship with the Horse model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // Ensure foreign and local keys match the schema
    }
    public function embryo()
    {
        return $this->belongsTo(Embryo::class, 'embryo_id', 'id'); // Ensure foreign and local keys match the schema
    }

}
