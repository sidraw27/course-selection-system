<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface CourseRepositoryInterface
{
    public function create(string $name, ?string $teacherId = null): ?Model;
    public function update(string $shortId, array $data): bool;
    public function delete(string $shortId): bool;
    public function findByShortId(string $shortId, array $columns = ['*']): ?Model;
}
