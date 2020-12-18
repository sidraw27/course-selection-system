<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, StudentSelectionCourse::class);
    }

    public function assistant(): HasOneThrough
    {
        return $this->hasOneThrough(Student::class, CourseAssistant::class);
    }
}
