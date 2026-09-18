<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function listForProject(
        Project $project,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->taskRepository->getForProject(
            $project,
            $filters,
            $perPage
        );
    }

    public function listForUser(
        User $user,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->taskRepository->getForUser(
            $user,
            $filters,
            $perPage
        );
    }

    public function getAssignableUsers(
        Project $project
    ): Collection {
        return $this->taskRepository->getAssignableUsers(
            $project
        );
    }

    public function getForView(
        Task $task
    ): Task {
        return $this->taskRepository->find(
            $task
        );
    }

    public function create(
        Project $project,
        array $data
    ): Task {
        return $this->taskRepository->create([
            'project_id' => $project->id,
            'assignee_id' => $data['assignee_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'todo',
            'priority' => $data['priority'] ?? 'medium',
            'due_date' => $data['due_date'] ?? null,
        ]);
    }

    public function update(
        Task $task,
        array $data
    ): Task {
        return $this->taskRepository->update(
            $task,
            $data
        );
    }

    public function delete(
        Task $task
    ): void {
        $this->taskRepository->delete(
            $task
        );
    }

    public function changeStatus(
        Task $task,
        string $status
    ): Task {
        return $this->taskRepository->updateStatus(
            $task,
            $status
        );
    }

    public function attachLabel(
        Task $task,
        int $labelId
    ): void {
        $this->taskRepository->attachLabel(
            $task,
            $labelId
        );
    }

    public function detachLabel(
        Task $task,
        int $labelId
    ): void {
        $this->taskRepository->detachLabel(
            $task,
            $labelId
        );
    }
}