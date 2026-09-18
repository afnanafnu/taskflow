<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getProjectsCount(
        User $user
    ): int {
        return Project::query()
            ->where('owner_id', $user->id)
            ->orWhereHas(
                'users',
                function ($query) use ($user) {
                    $query->whereKey($user->id);
                }
            )
            ->count();
    }

    public function getTasksCount(
        User $user
    ): int {
        return Task::query()
            ->where('assignee_id', $user->id)
            ->count();
    }

    public function getCompletedTasksCount(
        User $user
    ): int {
        return Task::query()
            ->where('assignee_id', $user->id)
            ->where('status', 'completed')
            ->count();
    }

    public function getPendingTasksCount(
        User $user
    ): int {
        return Task::query()
            ->where('assignee_id', $user->id)
            ->whereIn('status', [
                'todo',
                'in_progress',
                'blocked',
            ])
            ->count();
    }

    public function getAllProjectsCount(): int
    {
        return Project::query()->count();
    }

    public function getAllTasksCount(): int
    {
        return Task::query()->count();
    }

    public function getAllCompletedTasksCount(): int
    {
        return Task::query()
            ->where('status', 'completed')
            ->count();
    }

    public function getAllPendingTasksCount(): int
    {
        return Task::query()
            ->whereIn('status', [
                'todo',
                'in_progress',
                'blocked',
            ])
            ->count();
    }
}