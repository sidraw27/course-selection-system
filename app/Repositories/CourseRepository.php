<?php

namespace App\Repositories;

use App\Entities\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

final class CourseRepository extends AbstractRepository implements CourseRepositoryInterface
{
    public const SHORT_ID_LENGTH = 6;

    public function __construct(Course $entity)
    {
        parent::__construct($entity);
    }

    public function create(string $name, ?string $teacherId = null): ?Model
    {
        try {
            $data = [
                'short_id' => $this->generateShortId(self::SHORT_ID_LENGTH),
                'name'     => $name,
            ];

            if (null !== $teacherId) {
                $data['teacher_id'] = $teacherId;
            }

            return $this->entity->create($data);
        } catch (Exception $e) {
            // Log($e->getMessage())
            return null;
        }
    }

    public function update(string $shortId, array $data): bool
    {
        return $this->queryShortId($shortId)->update($data);
    }

    public function delete(string $shortId): bool
    {
        try {
            return $this->queryShortId($shortId)->first()->delete();
        } catch (Exception $e) {
            // Log
            return false;
        }
    }
}
