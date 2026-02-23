<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeddingDesc extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'bedding_descs';
    protected $fillable = [
        'feedbed_id',
        'horse_id',
        'item',
        'qty',
    ];
    function feedingbeding() : BelongsTo {
        return $this->belongsTo(FeedingBedding::class, 'feedbed_id', 'id');
    }
    function horse() : BelongsTo {
        return $this->belongsTo(Horse::class, 'horse_id', 'id');
    }
}
