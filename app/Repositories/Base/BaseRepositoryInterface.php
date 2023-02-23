<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{

    /**
     * This creates a new Model by the Model's properties
     *
     * @param array $attributes
     * @param array $relationships
     */
    public function createModel(array $attributes, array $relationships = []);

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function updateById(int $modelId, array $attributes): bool;

    /**
     *
     * @param array $queries
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findSingleByWhereClause(array $queries, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Get all Models or entities
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations= []): Collection;

    /**
     * Get all Trashed Models or entities
     *
     * @return Collection
     */
    public function getAllTrashed(): Collection;

    /**
     * Find Model by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations= [], array $appends = []): ?Model;

    /**
     *
     * @param string $columnToCount
     * @param array $queries
     * @return int
     */
    public function countRecords(string $columnToCount, array $queries = []): int;

    /**
     *
     * @param string $columnToSum
     * @param array $queries
     * @return float
     */
    public function sumRecords(string $columnToSum, array $queries = []): float;

    /**
     *
     * @param string $columnName
     * @return array
     */
    public function getAllTokens(string $columnName): array;
}
