<?php

namespace App\Services;

use App\Exceptions\CreateRecordFailedException;
use App\Repositories\Contracts\StudentRepositoryInterface;

final class StudentService
{
    protected StudentRepositoryInterface $repository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->repository = $studentRepository;
    }

    /**
     * @param string $name
     *
     * @return array
     * @throws CreateRecordFailedException
     */
    public function createStudent(string $name): array
    {
        $model = $this->repository->create($name);

        if (null === $model) {
            throw new CreateRecordFailedException();
        }

        return $model->only(['name', 'short_id']);
    }

    /**
     * @param string $shortId
     * @param string $name
     *
     * @return bool
     */
    public function updateStudent(string $shortId, string $name): bool
    {
        // 可針對字串做過濾處理
        $name = trim($name);

        return $this->repository->update($shortId, $name);
    }

    /**
     * @param string $shortId
     *
     * @return bool
     */
    public function deleteStudent(string $shortId): bool
    {
        return $this->repository->delete($shortId);
    }
}
