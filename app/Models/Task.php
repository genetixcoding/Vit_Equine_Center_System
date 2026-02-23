<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'tasks';
    protected $fillable = [
        'user_id',
    ];

    public function  user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function  taskdesc():HasMany
    {
        return $this->hasMany(TaskDesc::class);
    }

}
