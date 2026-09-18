<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->withCount([
                'ownedProjects',
                'assignedTasks',
                'comments',
            ])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(User $user): User
    {
        return $user->loadCount([
            'ownedProjects',
            'assignedTasks',
            'comments',
        ]);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function getAllExcept(User $user): Collection
    {
        return User::query()
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);
    }
}