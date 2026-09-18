<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Task;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository implements CommentRepositoryInterface
{
    public function getForTask(Task $task): Collection
    {
        return $task->comments()
            ->with([
                'user:id,name,email',
            ])
            ->latest()
            ->get();
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(
        Comment $comment,
        array $data
    ): Comment {
        $comment->update($data);

        return $comment->fresh([
            'user:id,name,email',
        ]);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}