<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function findOrFail(string $id): Model;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Model;

    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function insertMany(array $rows): bool;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Model $model, array $data): Model;

    public function delete(Model $model): bool;

    public function paginate(int $perPage = 15): LengthAwarePaginator;
}
