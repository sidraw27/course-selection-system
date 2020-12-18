<?php

namespace App\Repositories;

use App\Entities\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

final class StudentRepository extends AbstractRepository implements StudentRepositoryInterface
{
    public const SHORT_ID_LENGTH = 6;

    public function __construct(Student $entity)
    {
        parent::__construct($entity);
    }

    public function create(string $name): ?Model
    {
        try {
            return $this->entity->create([
                'short_id' => $this->generateShortId(self::SHORT_ID_LENGTH),
                'name'     => $name,
            ]);
        } catch (Exception $e) {
            // Log($e->getMessage())
            return null;
        }
    }

    public function update(string $shortId, string $name): bool
    {
        return $this->queryShortId($shortId)->update(['name' => $name]);
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
