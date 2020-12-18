<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'short_id',
        'name',
    ];

    public function selectionCourses(): HasMany
    {
        return $this->hasMany(StudentSelectionCourse::class);
    }

    public function courseAssistant(): HasMany
    {
        return $this->hasMany(CourseAssistant::class);
    }

    public static function boot()
    {
        self::deleting(function ($student) {
            $student->selectionCourses()->delete();
            $student->courseAssistant()->delete();
        });

        parent::boot();
    }
}
