<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    public function index(Task $task): View
    {
        $this->authorize(
            'viewAny',
            [Comment::class, $task]
        );

        $comments = $this->commentService->listForTask($task);

        return view('tasks.comments', compact(
            'task',
            'comments'
        ));
    }

    public function store(
        StoreCommentRequest $request,
        Task $task
    ): RedirectResponse {
        $this->authorize(
            'create',
            [Comment::class, $task]
        );

        $this->commentService->create(
            $task,
            $request->user(),
            $request->validated()
        );

        return back()->with(
            'success',
            'Comment added successfully.'
        );
    }

    public function update(
        UpdateCommentRequest $request,
        Comment $comment
    ): RedirectResponse {
        $this->authorize(
            'update',
            $comment
        );

        $this->commentService->update(
            $comment,
            $request->validated()
        );

        return back()->with(
            'success',
            'Comment updated successfully.'
        );
    }

    public function destroy(
        Comment $comment
    ): RedirectResponse {
        $this->authorize(
            'delete',
            $comment
        );

        $this->commentService->delete($comment);

        return back()->with(
            'success',
            'Comment deleted successfully.'
        );
    }
}