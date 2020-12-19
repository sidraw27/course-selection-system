<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface CourseAssistantRepositoryInterface
{
    public function updateOrCreateAssistant(string $courseId, string $studentId): ?Model;
    public function deleteAssistant(string $courseId, string $studentId): bool;
}
