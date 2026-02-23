<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Financial extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'financials';
    protected $fillable = [
        'amount',
        'decamount',
        'description',
    ];
    public function feedingbedding():HasMany
    {
        return $this->hasMany(FeedingBedding::class, 'finance_id', 'id');
    }
    public function salary():HasMany
    {
        return $this->hasMany(Salary::class, 'finance_id', 'id');
    }
    public function expenses():HasMany
    {
        return $this->hasMany(expenses::class, 'finance_id', 'id');
    }
    public function invoices():HasMany
    {
        return $this->hasMany(InternalInvoice::class, 'finance_id', 'id');
    }
    public function visit():HasMany
    {
        return $this->hasMany(Visit::class, 'finance_id', 'id');
    }
    public function embryo():HasMany
    {
        return $this->hasMany(Embryo::class, 'finance_id', 'id');
    }
    public function breeding():HasMany
    {
        return $this->hasMany(Breeding::class, 'finance_id', 'id');
    }


}
