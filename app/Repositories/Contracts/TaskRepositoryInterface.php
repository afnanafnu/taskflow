<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function getForProject(
        Project $project,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator;

    public function getForUser(
        User $user,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator;

    public function getAssignableUsers(
        Project $project
    ): Collection;

    public function find(
        Task $task
    ): Task;

    public function create(
        array $data
    ): Task;

    public function update(
        Task $task,
        array $data
    ): Task;

    public function delete(
        Task $task
    ): void;

    public function updateStatus(
        Task $task,
        string $status
    ): Task;

    public function attachLabel(
        Task $task,
        int $labelId
    ): void;

    public function detachLabel(
        Task $task,
        int $labelId
    ): void;
}