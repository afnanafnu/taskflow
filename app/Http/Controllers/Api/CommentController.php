<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    public function index(
        Task $task
    ): AnonymousResourceCollection {
        $comments = $this->commentService->listForTask(
            $task
        );

        return CommentResource::collection($comments);
    }

    public function store(
        StoreCommentRequest $request,
        Task $task
    ): CommentResource {
        $this->authorize(
            'create',
            [Comment::class, $task]
        );

        $comment = $this->commentService->create(
            $task,
            $request->user(),
            $request->validated()
        );

        return new CommentResource(
            $comment->load('user')
        );
    }

    public function update(
        UpdateCommentRequest $request,
        Comment $comment
    ): CommentResource {
        $this->authorize(
            'update',
            $comment
        );

        $comment = $this->commentService->update(
            $comment,
            $request->validated()
        );

        return new CommentResource($comment);
    }

    public function destroy(
        Comment $comment
    ): JsonResponse {
        $this->authorize(
            'delete',
            $comment
        );

        $this->commentService->delete(
            $comment
        );

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
            'data' => null,
        ]);
    }
}