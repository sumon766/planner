<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'is_active',
    ];

    public function interviewQuestions(): BelongsToMany
    {
        return $this->belongsToMany(InterviewQuestion::class);
    }
}
