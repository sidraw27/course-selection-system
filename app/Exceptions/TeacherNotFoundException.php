<?php

namespace App\Exceptions;

use Exception;

class TeacherNotFoundException extends Exception
{
    protected $message = '老師不存在';
}
