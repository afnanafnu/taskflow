<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task
        );
    }

    public function view(User $user, Comment $comment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $comment->task
        );
    }

    public function create(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->isProjectMember(
            $user,
            $task
        );
    }

    public function update(User $user, Comment $comment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $comment->user_id === $user->id
            && $this->isProjectMember(
                $user,
                $comment->task
            );
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $comment->user_id === $user->id
            && $this->isProjectMember(
                $user,
                $comment->task
            );
    }

    private function isProjectMember(
        User $user,
        Task $task
    ): bool {
        $project = $task->project;

        if ($project->owner_id === $user->id) {
            return true;
        }

        return $project->users()
            ->whereKey($user->id)
            ->exists();
    }
}