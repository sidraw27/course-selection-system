<?php

namespace App\Services;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\DuplicatedException;
use App\Exceptions\StudentNotFoundException;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\StudentSelectionCourseRepositoryInterface;
use App\Traits\StudentCourseHelper;
use Illuminate\Database\Eloquent\Model;

final class SelectionCourseService
{
    use StudentCourseHelper;

    protected StudentSelectionCourseRepositoryInterface $repository;
    protected StudentRepositoryInterface $studentRepository;
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(
        StudentSelectionCourseRepositoryInterface $studentSelectionCourseRepository,
        StudentRepositoryInterface $studentRepository,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->repository = $studentSelectionCourseRepository;
        $this->studentRepository = $studentRepository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param string $studentShortId
     * @param string $courseShortId
     *
     * @return Model|null
     * @throws CourseNotFoundException
     * @throws StudentNotFoundException
     * @throws DuplicatedException
     */
    public function addSelectionCourse(string $studentShortId, string $courseShortId): ?Model
    {
        $student = $this->getStudent($studentShortId);
        $course = $this->getCourse($courseShortId);

        return $this->repository
            ->createSelection(
                $student->getAttribute('id'), $course->getAttribute('id')
            );
    }

    /**
     * @param string $studentShortId
     * @param string $courseShortId
     *
     * @return bool
     * @throws CourseNotFoundException
     * @throws StudentNotFoundException
     */
    public function deleteSelectionCourse(string $studentShortId, string $courseShortId): bool
    {
        $student = $this->getStudent($studentShortId);
        $course = $this->getCourse($courseShortId);

        return $this->repository->deleteSelection(
            $student->getAttribute('id'), $course->getAttribute('id')
        );
    }
}
