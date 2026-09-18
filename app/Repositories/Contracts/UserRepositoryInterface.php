<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    public function find(User $user): User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;

    public function getAllExcept(User $user): \Illuminate\Support\Collection;
}