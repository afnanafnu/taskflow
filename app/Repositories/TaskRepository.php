<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get tasks for a specific project.
     */
    public function getForProject(
        Project $project,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = $project->tasks()
            ->with([
                'assignee:id,name,email',
                'labels:id,name,colour',
            ])
            ->withCount('comments');

        $this->applyFilters($query, $filters);

        return $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get tasks visible to a user.
     *
     * Admin:
     *     Can see every task.
     *
     * User:
     *     Can see tasks from projects they own/belong to
     *     or tasks assigned directly to them.
     */
    public function getForUser(
        User $user,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Task::query()
            ->with([
                'project:id,name,owner_id',
                'assignee:id,name,email',
                'labels:id,name,colour',
            ])
            ->withCount('comments');

        if (! $user->isAdmin()) {
            $query->where(function ($query) use ($user) {
                // Tasks from projects the user owns
                $query->whereHas('project', function ($projectQuery) use ($user) {
                    $projectQuery->where('owner_id', $user->id)
                        ->orWhereHas('users', function ($userQuery) use ($user) {
                            $userQuery->whereKey($user->id);
                        });
                });

                // Also include tasks directly assigned to the user
                $query->orWhere('assignee_id', $user->id);
            });
        }

        $this->applyFilters($query, $filters);

        return $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get users who belong to a project.
     */
    public function getAssignableUsers(
        Project $project
    ): Collection {
        return $project->users()
            ->select([
                'users.id',
                'users.name',
                'users.email',
            ])
            ->orderBy('users.name')
            ->get();
    }

    /**
     * Get task with relationships.
     */
    public function find(
        Task $task
    ): Task {
        return $task->load([
            'project:id,name,owner_id',
            'assignee:id,name,email',
            'labels:id,name,colour',
            'comments.user:id,name,email',
        ]);
    }

    /**
     * Create task.
     */
    public function create(
        array $data
    ): Task {
        return Task::create($data);
    }

    /**
     * Update task.
     */
    public function update(
        Task $task,
        array $data
    ): Task {
        $task->update($data);

        return $task->fresh([
            'project',
            'assignee',
            'labels',
        ]);
    }

    /**
     * Delete task.
     */
    public function delete(
        Task $task
    ): void {
        $task->delete();
    }

    /**
     * Update task status.
     */
    public function updateStatus(
        Task $task,
        string $status
    ): Task {
        $task->update([
            'status' => $status,
        ]);

        return $task->fresh([
            'project',
            'assignee',
            'labels',
        ]);
    }

    /**
     * Attach label.
     */
    public function attachLabel(
        Task $task,
        int $labelId
    ): void {
        $task->labels()->syncWithoutDetaching([
            $labelId,
        ]);
    }

    /**
     * Detach label.
     */
    public function detachLabel(
        Task $task,
        int $labelId
    ): void {
        $task->labels()->detach(
            $labelId
        );
    }

    /**
     * Apply task filters.
     */
    private function applyFilters(
        $query,
        array $filters
    ): void {
        if (! empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        if (! empty($filters['priority'])) {

            $query->where(
                'priority',
                $filters['priority']
            );
        }

        if (isset($filters['assignee_id'])) {

            $query->where(
                'assignee_id',
                $filters['assignee_id']
            );
        }

        if (! empty($filters['due_from'])) {

            $query->whereDate(
                'due_date',
                '>=',
                $filters['due_from']
            );
        }

        if (! empty($filters['due_to'])) {

            $query->whereDate(
                'due_date',
                '<=',
                $filters['due_to']
            );
        }

        if (! empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($query) use ($search) {

                $query->where(
                    'title',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                );
            });
        }
    }
}
