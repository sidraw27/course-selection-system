<?php

namespace App\Repositories;

use App\Entities\StudentSelectionCourse;
use App\Exceptions\DuplicatedException;
use App\Repositories\Contracts\StudentSelectionCourseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

final class StudentSelectionCourseRepository extends AbstractRepository implements StudentSelectionCourseRepositoryInterface
{
    public function __construct(StudentSelectionCourse $entity)
    {
        parent::__construct($entity);
    }

    /**
     * @param string $studentId
     * @param string $courseId
     *
     * @return Model|null
     * @throws DuplicatedException
     */
    public function createSelection(string $studentId, string $courseId): ?Model
    {
        try {
            return $this->entity->create([
                'student_id' => $studentId,
                'course_id' => $courseId,
            ]);
        } catch (Exception $e) {
            if ('23000' === $e->getCode()) {
                $entity = $this->entity
                    ->withTrashed()
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->first();

                if ($entity->trashed()) {
                    $entity->setAttribute('deleted_at', null);
                    $entity->save();
                } else {
                    throw new DuplicatedException();
                }
            }
            // Log($e->getMessage())
            return null;
        }
    }

    public function deleteSelection(string $studentId, string $courseId): bool
    {
        try {
            return $this->entity
                ->where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->delete();
        } catch (Exception $e) {
            // Log
            return false;
        }
    }
}
