<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function getForUser(
        User $user,
        int $perPage = 15
    ): LengthAwarePaginator;

    public function create(array $data): Project;

    public function getForView(Project $project): Project;

    public function update(
        Project $project,
        array $data
    ): Project;

    public function delete(Project $project): void;

    public function attachUser(
        Project $project,
        User $user,
        string $role
    ): void;

    public function attachUsers(
        Project $project,
        array $userIds,
        string $role = 'member'
    ): void;

    public function syncUsers(
        Project $project,
        array $userIds,
        User $owner
    ): void;

    public function removeUser(
        Project $project,
        User $user
    ): void;

    public function unassignTasks(
        Project $project,
        User $user
    ): void;
}