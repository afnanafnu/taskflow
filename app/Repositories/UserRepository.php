<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getAllExcept(User $user): Collection
    {
        return User::query()
            ->whereKeyNot($user->id)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);
    }
}