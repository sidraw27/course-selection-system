<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface StudentRepositoryInterface
{
    public function create(string $name): ?Model;
    public function update(string $shortId, string $name): bool;
    public function delete(string $shortId): bool;
}
