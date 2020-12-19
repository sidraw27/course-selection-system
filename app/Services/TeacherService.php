<?php

namespace App\Services;

use App\Exceptions\CreateRecordFailedException;
use App\Repositories\Contracts\TeacherRepositoryInterface;

final class TeacherService
{
    protected TeacherRepositoryInterface $repository;

    public function __construct(TeacherRepositoryInterface $studentRepository)
    {
        $this->repository = $studentRepository;
    }

    /**
     * @param string $name
     *
     * @return array
     * @throws CreateRecordFailedException
     */
    public function createTeacher(string $name): array
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
    public function updateTeacher(string $shortId, string $name): bool
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
    public function deleteTeacher(string $shortId): bool
    {
        return $this->repository->delete($shortId);
    }
}
