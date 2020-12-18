<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSelectionCourse extends Model
{
    use HasFactory;
    use SoftDeletes;
}
