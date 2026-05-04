<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Grade;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'classroom_id',
        'name',
        'type',
        'max_score',
        'scheduled_at',
        'duration_minutes',
        'is_online',
        'instructions',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_online' => 'boolean',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}

