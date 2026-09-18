<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface DashboardRepositoryInterface
{
    public function getProjectsCount(
        User $user
    ): int;

    public function getTasksCount(
        User $user
    ): int;

    public function getCompletedTasksCount(
        User $user
    ): int;

    public function getPendingTasksCount(
        User $user
    ): int;

    public function getAllProjectsCount(): int;

    public function getAllTasksCount(): int;

    public function getAllCompletedTasksCount(): int;

    public function getAllPendingTasksCount(): int;
}