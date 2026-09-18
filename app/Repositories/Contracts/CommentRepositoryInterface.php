<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function getForTask(Task $task): Collection;

    public function create(array $data): Comment;

    public function update(
        Comment $comment,
        array $data
    ): Comment;

    public function delete(Comment $comment): void;
}