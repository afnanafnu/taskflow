<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachTaskLabelRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function index(
        TaskIndexRequest $request,
        Project $project
    ): AnonymousResourceCollection {
        $this->authorize(
            'viewAny',
            [Task::class, $project]
        );

        $filters = $request->validated();

        $tasks = $this->taskService->listForProject(
            $project,
            $filters,
            $request->integer('per_page', 15)
        );

        return TaskResource::collection($tasks);
    }

    public function store(
        StoreTaskRequest $request,
        Project $project
    ): TaskResource {
        $this->authorize(
            'create',
            [Task::class, $project]
        );

        $task = $this->taskService->create(
            $project,
            $request->validated()
        );

        return new TaskResource(
            $task->load([
                'project',
                'assignee',
                'labels',
            ])
        );
    }

    public function show(
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'view',
            $task
        );

        return new TaskResource(
            $this->taskService->find($task)
        );
    }

    public function update(
        UpdateTaskRequest $request,
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'update',
            $task
        );

        $task = $this->taskService->update(
            $project,
            $task,
            $request->validated()
        );

        return new TaskResource($task);
    }

    public function destroy(
        Project $project,
        Task $task
    ): JsonResponse {
        $this->authorize(
            'delete',
            $task
        );

        $this->taskService->delete($task);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
            'data' => null,
        ]);
    }

    public function status(
        UpdateTaskStatusRequest $request,
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'changeStatus',
            $task
        );

        $task = $this->taskService->updateStatus(
            $task,
            $request->validated('status')
        );

        return new TaskResource($task);
    }

    public function attachLabel(
        AttachTaskLabelRequest $request,
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'attachLabel',
            $task
        );

        $task = $this->taskService->attachLabel(
            $task,
            $request->validated('label_id')
        );

        return new TaskResource($task);
    }

    public function detachLabel(
        Project $project,
        Task $task,
        Label $label
    ): TaskResource {
        $this->authorize(
            'detachLabel',
            $task
        );

        $task = $this->taskService->detachLabel(
            $task,
            $label->id
        );

        return new TaskResource($task);
    }
}
