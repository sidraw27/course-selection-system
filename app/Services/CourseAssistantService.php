<?php

namespace App\Services;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\StudentNotFoundException;
use App\Repositories\Contracts\CourseAssistantRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\StudentSelectionCourseRepositoryInterface;
use App\Traits\StudentCourseHelper;
use Illuminate\Database\Eloquent\Model;

final class CourseAssistantService
{
    use StudentCourseHelper;

    protected CourseAssistantRepositoryInterface $repository;
    protected StudentSelectionCourseRepositoryInterface $studentSelectionCourseRepository;
    protected StudentRepositoryInterface $studentRepository;
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(
        CourseAssistantRepositoryInterface $courseAssistantRepository,
        StudentSelectionCourseRepositoryInterface $studentSelectionCourseRepository,
        StudentRepositoryInterface $studentRepository,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->repository = $courseAssistantRepository;
        $this->studentSelectionCourseRepository = $studentSelectionCourseRepository;
        $this->studentRepository = $studentRepository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param string $courseShortId
     * @param string $studentShortId
     *
     * @return Model|null
     * @throws CourseNotFoundException
     * @throws StudentNotFoundException
     */
    public function putAssistant(string $courseShortId, string $studentShortId): ?Model
    {
        $student = $this->getStudent($studentShortId);
        $course = $this->getCourse($courseShortId);

        return $this->repository
            ->updateOrCreateAssistant(
                $course->getAttribute('id'), $student->getAttribute('id')
            );
    }

    /**
     * @param string $courseShortId
     * @param string $studentShortId
     *
     * @return bool
     * @throws CourseNotFoundException
     * @throws StudentNotFoundException
     */
    public function removeAssistant(string $courseShortId, string $studentShortId): bool
    {
        $student = $this->getStudent($studentShortId);
        $course = $this->getCourse($courseShortId);

        return $this->repository->deleteAssistant(
            $course->getAttribute('id'),
            $student->getAttribute('id')
        );
    }
}
