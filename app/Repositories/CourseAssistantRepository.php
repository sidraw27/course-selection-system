<?php

namespace App\Repositories;

use App\Entities\CourseAssistant;
use App\Exceptions\DuplicatedException;
use App\Repositories\Contracts\CourseAssistantRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

final class CourseAssistantRepository extends AbstractRepository implements CourseAssistantRepositoryInterface
{
    public function __construct(CourseAssistant $entity)
    {
        parent::__construct($entity);
    }

    /**
     * 目前助教僅限一人，之後若有多助教需修改 condition
     *
     * @param string $courseId
     * @param string $studentId
     *
     * @return Model|null
     * @throws DuplicatedException
     */
    public function updateOrCreateAssistant(string $courseId, string $studentId): ?Model
    {
        try {
            return $this->entity->updateOrCreate([
                'course_id' => $courseId,
            ], [
                'course_id' => $courseId,
                'student_id' => $studentId,
            ]);
        } catch (Exception $e) {
            if ('23000' === $e->getCode()) {
                $entity = $this->entity
                    ->withTrashed()
                    ->where('course_id', $courseId)
                    ->first();

                if ( ! $entity->trashed()) {
                    throw new DuplicatedException();
                }

                $entity->setAttribute('deleted_at', null);
                $entity->save();
            }
            // Log($e->getMessage())
            return null;
        }
    }

    public function deleteAssistant(string $courseId, string $studentId): bool
    {
        try {
            return $this->entity
                ->where('course_id', $courseId)
                ->where('student_id', $studentId)
                ->delete();
        } catch (Exception $e) {
            // Log
            return false;
        }
    }
}
