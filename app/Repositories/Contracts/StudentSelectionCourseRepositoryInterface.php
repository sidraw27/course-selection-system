<?php

namespace App\Repositories\Contracts;

use App\Exceptions\DuplicatedException;
use Illuminate\Database\Eloquent\Model;

interface StudentSelectionCourseRepositoryInterface
{
    /**
     * @param string $studentId
     * @param string $courseId
     *
     * @return Model|null
     * @throws DuplicatedException
     */
    public function createSelection(string $studentId, string $courseId): ?Model;
    public function deleteSelection(string $studentId, string $courseId): bool;
}
