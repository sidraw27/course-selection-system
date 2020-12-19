<?php

namespace App\Exceptions;

use Exception;

class CourseNotFoundException extends Exception
{
    protected $message = '課程不存在';
}
