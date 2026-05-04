<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Submission;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'assigned_at',
        'due_at',
        'points',
        'is_active',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'due_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}

