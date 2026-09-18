<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    /**
     * General task page.
     *
     * /tasks
     */
    public function all(
        Request $request
    ): View {
        $this->authorize(
            'viewAny',
            Task::class
        );

        $tasks = $this->taskService->listForUser(
            $request->user(),
            $request->only([
                'filter',
                'status',
                'priority',
                'assignee_id',
                'search',
            ])
        );

        return view(
            'tasks.index',
            compact('tasks')
        );
    }

    /**
     * Tasks inside a project.
     *
     * /projects/{project}/tasks
     */
    public function index(
        Project $project
    ): View {
        $this->authorize(
            'viewAnyForProject',
            [Task::class, $project]
        );

        $tasks = $this->taskService->listForProject(
            $project
        );

        return view(
            'tasks.index',
            compact(
                'project',
                'tasks'
            )
        );
    }

    /**
     * Create task.
     */
    public function create(
        Project $project
    ): View {
        $this->authorize(
            'create',
            [Task::class, $project]
        );

        $members = $this->taskService
            ->getAssignableUsers($project);

        return view(
            'tasks.create',
            compact(
                'project',
                'members'
            )
        );
    }

    /**
     * Store task.
     */
    public function store(
        StoreTaskRequest $request,
        Project $project
    ): RedirectResponse {
        $this->authorize(
            'create',
            [Task::class, $project]
        );

        $task = $this->taskService->create(
            $project,
            $request->validated()
        );

        return redirect()
            ->route(
                'projects.tasks.show',
                [$project, $task]
            )
            ->with(
                'success',
                'Task created successfully.'
            );
    }

    /**
     * Show task.
     */
    public function show(
        Project $project,
        Task $task
    ): View {
        $this->authorize(
            'view',
            $task
        );

        $task = $this->taskService->getForView(
            $task
        );

        return view(
            'tasks.show',
            compact(
                'project',
                'task'
            )
        );
    }

    /**
     * Edit task.
     */
    public function edit(
        Project $project,
        Task $task
    ): View {
        $this->authorize(
            'update',
            $task
        );

        $members = $this->taskService
            ->getAssignableUsers($project);

        return view(
            'tasks.edit',
            compact(
                'project',
                'task',
                'members'
            )
        );
    }

    /**
     * Update task.
     */
    public function update(
        UpdateTaskRequest $request,
        Project $project,
        Task $task
    ): RedirectResponse {
        $this->authorize(
            'update',
            $task
        );

        $task = $this->taskService->update(
            $task,
            $request->validated()
        );

        return redirect()
            ->route(
                'projects.tasks.show',
                [$project, $task]
            )
            ->with(
                'success',
                'Task updated successfully.'
            );
    }

    /**
     * Delete task.
     */
    public function destroy(
        Project $project,
        Task $task
    ): RedirectResponse {
        $this->authorize(
            'delete',
            $task
        );

        $this->taskService->delete(
            $task
        );

        return redirect()
            ->route(
                'projects.tasks.index',
                $project
            )
            ->with(
                'success',
                'Task deleted successfully.'
            );
    }

    /**
     * Change task status.
     */
    public function status(
        UpdateTaskStatusRequest $request,
        Project $project,
        Task $task
    ): RedirectResponse {
        $this->authorize(
            'changeStatus',
            $task
        );

        $this->taskService->changeStatus(
            $task,
            $request->validated()['status']
        );

        return back()->with(
            'success',
            'Task status updated successfully.'
        );
    }
}