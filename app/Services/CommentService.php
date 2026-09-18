<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {}

    public function listForTask(Task $task): Collection
    {
        return $this->commentRepository->getForTask($task);
    }

    public function create(
        Task $task,
        User $user,
        array $data
    ): Comment {
        return $this->commentRepository->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);
    }

    public function update(
        Comment $comment,
        array $data
    ): Comment {
        return $this->commentRepository->update(
            $comment,
            $data
        );
    }

    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}