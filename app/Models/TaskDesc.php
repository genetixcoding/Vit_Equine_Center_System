<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskDesc extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'task_descs';
    protected $fillable = [
        'horse_id',
        'task_id',
        'task',
        'status',
    ];
    public function  task():BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function  user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function  horse():BelongsTo
    {
        return $this->belongsTo(Horse::class, 'horse_id');
    }
}
