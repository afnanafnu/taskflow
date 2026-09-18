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

    /**
     * List tasks inside a project.
     */
    public function index(
        TaskIndexRequest $request,
        Project $project
    ): AnonymousResourceCollection {
        $this->authorize(
            'viewAnyForProject',
            [Task::class, $project]
        );

        $tasks = $this->taskService->listForProject(
            $project,
            $request->validated(),
            $request->integer('per_page', 15)
        );

        return TaskResource::collection($tasks);
    }

    /**
     * Create a task.
     */
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
                'project:id,name,owner_id',
                'assignee:id,name,email',
                'labels:id,name,colour',
            ])
        );
    }

    /**
     * Show a task.
     */
    public function show(
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'view',
            $task
        );

        $task = $this->taskService->getForView(
            $task
        );

        return new TaskResource($task);
    }

    /**
     * Update a task.
     */
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
            $task,
            $request->validated()
        );

        return new TaskResource($task);
    }

    /**
     * Delete a task.
     */
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

    /**
     * Change task status.
     */
    public function status(
        UpdateTaskStatusRequest $request,
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'changeStatus',
            $task
        );

        $task = $this->taskService->changeStatus(
            $task,
            $request->validated()['status']
        );

        return new TaskResource($task);
    }

    /**
     * Attach a label to a task.
     */
    public function attachLabel(
        AttachTaskLabelRequest $request,
        Project $project,
        Task $task
    ): TaskResource {
        $this->authorize(
            'attachLabel',
            $task
        );

        $this->taskService->attachLabel(
            $task,
            $request->validated()['label_id']
        );

        $task = $this->taskService->getForView(
            $task
        );

        return new TaskResource($task);
    }

    /**
     * Detach a label from a task.
     */
    public function detachLabel(
        Project $project,
        Task $task,
        Label $label
    ): TaskResource {
        $this->authorize(
            'detachLabel',
            $task
        );

        $this->taskService->detachLabel(
            $task,
            $label->id
        );

        $task = $this->taskService->getForView(
            $task
        );

        return new TaskResource($task);
    }
}