<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'description',
    ] ;
    public function internalinvoices()
    {
        return $this->hasMany(InternalInvoice::class);
    }
    public function feedbed()
    {
        return $this->hasMany(FeedingBedding::class);
    }

}
