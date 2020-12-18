<?php

namespace App\Providers;

use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\StudentRepository;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class RepositoryBindServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
    }
}
