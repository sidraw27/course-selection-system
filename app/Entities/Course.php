<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'short_id',
        'teacher_id',
        'name',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            StudentSelectionCourse::class,
            'course_id', 'id', 'id', 'student_id'
        );
    }

    public function assistant(): HasOneThrough
    {
        return $this->hasOneThrough(
            Student::class,
            CourseAssistant::class,
            'course_id', 'id', 'id', 'student_id'
        );
    }
}
