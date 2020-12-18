<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AbstractRepository
 *
 * @package App\Repositories
 *
 * @property Builder|Model $entity
 */
abstract class AbstractRepository
{
    protected Model $entity;

    public function __construct(Model $entity)
    {
        $this->entity = $entity;
    }

    protected function generateShortId(int $len = 6): string
    {
        $atoms = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle($atoms);
        $shortId = implode(array_slice($atoms, 1, $len));

        return $this->isShortIdExists($shortId) ? $this->generateShortId($len) : $shortId;
    }

    protected function queryShortId(string $shortId): Builder
    {
        return $this->entity->where('short_id', $shortId);
    }

    private function isShortIdExists(string $shortId): bool
    {
        return $this->entity->where('short_id', $shortId)->exists();
    }
}
