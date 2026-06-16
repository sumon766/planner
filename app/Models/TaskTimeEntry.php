<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTimeEntry extends Model
{
    protected $fillable = [
        'user_id',
        'routine_task_id',
        'parent_task_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'note',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(RoutineTask::class, 'routine_task_id');
    }

    // parent task reference (useful for grouping subtask time)
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(RoutineTask::class, 'parent_task_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isRunning(): bool
    {
        return is_null($this->ended_at);
    }

    public function getDurationInSeconds(): int
    {
        if ($this->ended_at) {
            return $this->duration_seconds ?? $this->ended_at->diffInSeconds($this->started_at);
        }

        return now()->diffInSeconds($this->started_at);
    }
}
