<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * General task page.
     *
     * Used by:
     * GET /tasks
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tasks inside a specific project.
     *
     * Used by:
     * GET /projects/{project}/tasks
     */
    public function viewAnyForProject(
        User $user,
        Project $project
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $project
        );
    }

    /**
     * Create task inside a project.
     */
    public function create(
        User $user,
        Project $project
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $project
        );
    }

    /**
     * View a task.
     */
    public function view(
        User $user,
        Task $task
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task->project
        );
    }

    /**
     * Update task.
     */
    public function update(
        User $user,
        Task $task
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task->project
        );
    }

    /**
     * Delete task.
     */
    public function delete(
        User $user,
        Task $task
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task->project
        );
    }

    /**
     * Change task status.
     */
    public function changeStatus(
        User $user,
        Task $task
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task->project
        );
    }

    /**
     * Attach label.
     */
    public function attachLabel(
        User $user,
        Task $task
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task->project
        );
    }

    /**
     * Detach label.
     */
    public function detachLabel(
        User $user,
        Task $task
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task->project
        );
    }

    /**
     * Check project membership.
     */
    private function isProjectMember(
        User $user,
        Project $project
    ): bool {
        if ($project->owner_id === $user->id) {
            return true;
        }

        return $project->users()
            ->whereKey($user->id)
            ->exists();
    }
}