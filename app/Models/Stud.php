<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stud extends Model
{
    use HasFactory;

    protected $table = 'studs';
    protected $fillable = [
        'name',
        'description',
        'status',
        'image',

    ];
     public function breedings()
    {
        $horseCollection = $this->horse ?? collect();
        $horseIds = $horseCollection->pluck('id')->toArray();
        return Breeding::whereIn('femalehorse', $horseIds)
                       ->orWhereIn('malehorse', $horseIds)
                       ->with(['femaleHorse', 'maleHorse', 'embryo']);
    }

    // في موديل Stud
    public function embryos()
    {
        // نجيب كل البريدنج أولاً
        $allBreedings = $this->breedings()->get();

        // نجمع كل الأمبريو من كل بريدنج، ونشيل التكرار
        return $allBreedings
            ->flatMap(fn($breeding) => $breeding->embryo) // أو embryos لو اسم العلاقة جمع
            ->unique('id')
            ->values();
    }


    public function horse():HasMany
    {
        return $this->hasMany(Horse::class, 'stud_id', 'id');
    }
    public function users():HasMany
    {
        return $this->hasMany(User::class, 'stud_id', 'id');
    }

    /**
     * Get the visits for the stud.
     */
    public function visits():HasMany
    {
        return $this->hasMany(Visit::class);
    }
    public function externalinvoices():HasMany
    {
        return $this->hasMany(ExternalInvoice::class);
    }
}
