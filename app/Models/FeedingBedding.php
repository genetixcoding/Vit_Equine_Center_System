<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedingBedding extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'feeding_beddings';
    protected $fillable = [
        'finance_id',
        'supplier_id',
        'item',
        'qty',
        'price',
        'decqty',
        'paid'
    ];
    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function finance():BelongsTo
    {
        return $this->belongsTo(Financial::class, 'finance_id', 'id');
    }
    public function feeding():HasMany
    {
        return $this->hasMany(FeedingDesc::class, 'feedbed_id', 'id');
    }
    public function bedding():HasMany
    {
        return $this->hasMany(BeddingDesc::class, 'feedbed_id', 'id');
    }

    // Automatically calculate unitprice before saving
   public function getUnitpriceAttribute()
    {
        if ($this->qty != 0 && $this->qty !== null) {
            return bcdiv($this->price, $this->qty, 2); // تحسب بدقة 2 أرقام عشرية
        }
        return 0;
    }

}

