<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentDesc extends Model
{
    use HasFactory;
    protected $table = 'treatment_descs';

    public $timestamps = true;
    protected $fillable = [
        'pharmacy_id',
        'description',
        'qty',
        'type',

    ];
    // Define the relationship with Treatment
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatment_id');
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }
}
