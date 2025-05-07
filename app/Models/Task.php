<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'is_subtask', 'parent_id',
    ];

    // Define relationships, if needed, like:
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subTasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}
