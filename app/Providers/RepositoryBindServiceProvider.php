<?php

namespace App\Providers;

use App\Repositories\Contracts\CourseAssistantRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\StudentSelectionCourseRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\CourseAssistantRepository;
use App\Repositories\CourseRepository;
use App\Repositories\StudentRepository;
use App\Repositories\StudentSelectionCourseRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class RepositoryBindServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(
            StudentSelectionCourseRepositoryInterface::class,
            StudentSelectionCourseRepository::class
        );
        $this->app->bind(
            CourseAssistantRepositoryInterface::class,
            CourseAssistantRepository::class
        );
    }
}
