<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepositoryInterface $dashboardRepository
    ) {}

    public function getStatistics(
        User $user
    ): array {
        if ($user->isAdmin()) {
            return [
                'projectsCount' =>
                    $this->dashboardRepository
                        ->getAllProjectsCount(),

                'tasksCount' =>
                    $this->dashboardRepository
                        ->getAllTasksCount(),

                'completedTasks' =>
                    $this->dashboardRepository
                        ->getAllCompletedTasksCount(),

                'pendingTasks' =>
                    $this->dashboardRepository
                        ->getAllPendingTasksCount(),
            ];
        }

        return [
            'projectsCount' =>
                $this->dashboardRepository
                    ->getProjectsCount($user),

            'tasksCount' =>
                $this->dashboardRepository
                    ->getTasksCount($user),

            'completedTasks' =>
                $this->dashboardRepository
                    ->getCompletedTasksCount($user),

            'pendingTasks' =>
                $this->dashboardRepository
                    ->getPendingTasksCount($user),
        ];
    }
}