<?php

namespace App\Repositories\Base;


use App\Utils\Utils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepositoryAbstract implements BaseRepositoryInterface
{

    /**
     * The model class to be used
     *
     * @var Model
     */
    protected Model $model;

    /**
     * The database table name of the model
     *
     * @var string
     */
    protected string $databaseTableName;

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     * @param string $databaseTableName
     */
    public function __construct(Model $model, string $databaseTableName)
    {
        $this->model             = $model;
        $this->databaseTableName = $databaseTableName;
    }

    /**
     * This creates a new Model by the Model's properties
     *
     * @param array $attributes
     * @param array $relationships
     * @return Model
     */
    public function createModel(array $attributes, array $relationships = []): Model
    {
        $model = $this->model::create($attributes);

        return $this->findById($model->id, ['*'], $relationships);
    }

    /**
     * Find Model by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->sharedLock()->find($modelId);
    }

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function updateById(int $modelId, array $attributes): bool
    {
        $model = $this->findById($modelId);

        return $model->update($attributes);
    }

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @return Model
     */
    public function updateByIdAndGetBackRecord(int $modelId, array $attributes): Model
    {
        $model = $this->findById($modelId);
        $model->update($attributes);

        return $this->findById($modelId);
    }

    /**
     * Get all Models or entities
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->sharedLock()->get($columns);
    }

    /**
     *
     * @param string $columnName
     * @return array
     */
    public function getAllTokens(string $columnName = 'reference'): array
    {
        return (array) $this->model::sharedLock()->pluck($columnName);
    }

    /**
     * Get all Trashed Models or entities
     *
     * @return Collection
     */
    public function getAllTrashed(): Collection
    {
        return $this->model->onlyTrashed()->sharedLock()->get();
    }

    /**
     *
     * @param array $queries
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findSingleModelByKeyValuePair(array $queries, array $columns = ['*'], array $relations = []): ?Model
    {
        $query = $this->model->with($relations)->select($columns);

        return Utils::getRecordUsingWhereArrays($query, $queries)->sharedLock()->latest()->first();
    }

    /**
     *
     * @param string $columnToCount
     * @param array $queries
     * @return int
     */
    public function countRecords(string $columnToCount, array $queries = []): int
    {
        return $this->model::where($queries)->sharedLock()->count($columnToCount);
    }

    /**
     *
     * @param string $columnToSum
     * @param array $queries
     * @return float
     */
    public function sumRecords(string $columnToSum, array $queries = []): float
    {
        return $this->model::where($queries)->sharedLock()->sum($columnToSum);
    }

    /**
     * Find Model by column name and value
     *
     * @param string $columnName
     * @param string|null $value
     * @param array $columns
     * @param array $relations
     * @return array
     */
    public function findRecordsByColumnAndValue(string $columnName, ?string $value = null, array $columns = ['*'], array $relations = []): array
    {
        $records = [];
        $results = $this->model->with($relations)->select($columns)->where($columnName, $value)->sharedLock()->orderByDesc('id')->get();

        if (count($results))
            foreach ($results as $result)
                $records[] = $result;

        return $records;
    }
}
