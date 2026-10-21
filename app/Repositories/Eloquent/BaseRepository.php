<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function findOrFail(string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Model
    {
        return $this->query()->create($data);
    }

    /**
     * One INSERT query; fillAndInsert() still sets UUIDs, timestamps and casts.
     *
     * @param  list<array<string, mixed>>  $rows
     */
    public function insertMany(array $rows): bool
    {
        return $this->query()->fillAndInsert($rows);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model;
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->latest()->paginate($perPage);
    }

    /**
     * Fresh query builder, so no state leaks between calls.
     */
    protected function query(): Builder
    {
        return $this->model->newQuery();
    }
}
