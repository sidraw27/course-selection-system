<?php

namespace App\Exceptions;

use Exception;

class StudentNotFoundException extends Exception
{
    protected $message = '學生不存在';
}
