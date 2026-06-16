<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutineTask extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'title',
        'description',
        'is_active',
        'weekdays',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weekdays' => 'array',
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

    // Parent task (if this is a subtask)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Subtasks
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Time logs for this task
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TaskTimeEntry::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isSubTask(): bool
    {
        return !is_null($this->parent_id);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
