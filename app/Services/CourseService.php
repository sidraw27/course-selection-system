<?php

namespace App\Services;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\CreateRecordFailedException;
use App\Exceptions\TeacherNotFoundException;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;

final class CourseService
{
    protected CourseRepositoryInterface $repository;
    protected TeacherRepositoryInterface $teacherRepository;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        TeacherRepositoryInterface $teacherRepository
    ) {
        $this->repository = $courseRepository;
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * @param string $shortId
     *
     * @return array
     * @throws CourseNotFoundException
     */
    public function getCourseInfo(string $shortId): array
    {
        $course = $this->repository->findDetailInfoCourse($shortId);

        if (null === $course) {
            throw new CourseNotFoundException();
        }

        return [
            'info'      => $course->only(['short_id', 'name']),
            'teacher'   => $course->getAttribute('teacher')->only(['short_id', 'name']),
            'assistant' => $course->getAttribute('assistant')->only(['short_id', 'name']),
            'students'  => $course->getAttribute('students')->map(function ($student) {
                return $student->only(['short_id', 'name']);
            })->all()
        ];
    }

    /**
     * @param string $name
     * @param string|null $teacherShortId
     *
     * @return array
     * @throws TeacherNotFoundException
     * @throws CreateRecordFailedException
     */
    public function createCourse(string $name, ?string $teacherShortId = null): array
    {
        $teacherId = $this->getTeacherId($teacherShortId);

        $model = $this->repository->create($name, $teacherId);

        if (null === $model) {
            throw new CreateRecordFailedException();
        }

        return $model->only(['name', 'short_id', 'teacher_id']);
    }

    /**
     * @param string $shortId
     * @param string|null $name
     * @param string|null $teacherShortId
     *
     * @return bool
     * @throws TeacherNotFoundException
     */
    public function updateCourse(string $shortId, ?string $name = null, ?string $teacherShortId = null): bool
    {
        $updateData = [];

        if (null !== $name) {
            $updateData['name'] = trim($name);
        }

        if (null !== $teacherShortId) {
            $teacherId = $this->getTeacherId($teacherShortId);
            $updateData['teacher_id'] = $teacherId;
        }

        if ([] === $updateData) {
            return true;
        }

        return $this->repository->update($shortId, $updateData);
    }

    public function deleteCourse(string $shortId): bool
    {
        return $this->repository->delete($shortId);
    }

    /**
     * @param string $courseShortId
     *
     * @return array
     * @throws CourseNotFoundException
     */
    public function getSelectedStudent(string $courseShortId): array
    {
        $course = $this->repository->findWithStudents($courseShortId);

        if (null === $course) {
            throw new CourseNotFoundException();
        }

        return $course->getAttribute('students')->map(function ($student) {
            return $student->only(['short_id', 'name']);
        })->all();
    }

    /**
     * @param string|null $teacherShortId
     *
     * @return int
     * @throws TeacherNotFoundException
     */
    private function getTeacherId(?string $teacherShortId): ?int
    {
        $teacherId = null;

        if (null !== $teacherShortId) {
            $teacher = $this->teacherRepository->findByShortId($teacherShortId);

            if (null === $teacher) {
                throw new TeacherNotFoundException();
            }

            $teacherId = $teacher->getAttribute('id');
        }

        return $teacherId;
    }
}
