<?php

namespace App\Traits;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\StudentNotFoundException;
use Illuminate\Database\Eloquent\Model;

trait StudentCourseHelper
{
    /**
     * @param string $studentShortId
     *
     * @return Model
     * @throws StudentNotFoundException
     */
    private function getStudent(string $studentShortId): Model
    {
        $student = $this->studentRepository->findByShortId($studentShortId, ['id']);

        if (null === $student) {
            throw new StudentNotFoundException();
        }

        return $student;
    }

    /**
     * @param string $courseShortId
     *
     * @return Model
     * @throws CourseNotFoundException
     */
    private function getCourse(string $courseShortId): Model
    {
        $course = $this->courseRepository->findByShortId($courseShortId, ['id']);

        if (null === $course) {
            throw new CourseNotFoundException();
        }

        return $course;
    }
}
