<x-taskflow-layout>
    <x-slot name="title">
        {{ isset($project) ? $project->name . ' Tasks' : 'All Tasks' }}
    </x-slot>

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    {{ isset($project) ? $project->name . ' Tasks' : 'All Tasks' }}
                </h2>

                <p class="text-muted mb-0">
                    {{ isset($project)
                        ? 'Manage tasks for this project.'
                        : 'View and manage your accessible tasks.'
                    }}
                </p>
            </div>

            @isset($project)
                @can('create', [App\Models\Task::class, $project])
                    <a
                        href="{{ route('projects.tasks.create', $project) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Create Task
                    </a>
                @endcan
            @endisset
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <form method="GET" action="{{ request()->routeIs('tasks.index') && !isset($project) ? route('tasks.index') : route('projects.tasks.index', $project) }}">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Search</label>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Search tasks..."
                            >
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">
                                <option value="">All</option>

                                <option
                                    value="todo"
                                    @selected(request('status') === 'todo')
                                >
                                    To Do
                                </option>

                                <option
                                    value="in_progress"
                                    @selected(request('status') === 'in_progress')
                                >
                                    In Progress
                                </option>

                                <option
                                    value="completed"
                                    @selected(request('status') === 'completed')
                                >
                                    Completed
                                </option>

                                <option
                                    value="blocked"
                                    @selected(request('status') === 'blocked')
                                >
                                    Blocked
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Priority</label>

                            <select name="priority" class="form-select">
                                <option value="">All</option>

                                <option
                                    value="low"
                                    @selected(request('priority') === 'low')
                                >
                                    Low
                                </option>

                                <option
                                    value="medium"
                                    @selected(request('priority') === 'medium')
                                >
                                    Medium
                                </option>

                                <option
                                    value="high"
                                    @selected(request('priority') === 'high')
                                >
                                    High
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>
                                Filter
                            </button>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a
                                href="{{ isset($project)
                                    ? route('projects.tasks.index', $project)
                                    : route('tasks.index') }}"
                                class="btn btn-outline-secondary w-100"
                            >
                                Reset
                            </a>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        {{-- Tasks --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 p-4">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            Tasks
                        </h5>

                        <p class="text-muted small mb-0">
                            {{ $tasks->total() }} task(s)
                        </p>
                    </div>

                </div>
            </div>

            <div class="card-body p-0">

                @if($tasks->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Task</th>
                                    <th>Project</th>
                                    <th>Assignee</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Due Date</th>
                                    <th class="text-end px-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($tasks as $task)

                                    <tr>

                                        {{-- Task --}}
                                        <td class="px-4">

                                            <div class="fw-semibold">
                                                {{ $task->title }}
                                            </div>

                                            @if($task->description)
                                                <div class="text-muted small text-truncate" style="max-width: 280px;">
                                                    {{ $task->description }}
                                                </div>
                                            @endif

                                        </td>

                                        {{-- Project --}}
                                        <td>

                                            @if($task->project)

                                                <a
                                                    href="{{ route('projects.show', $task->project) }}"
                                                    class="text-decoration-none"
                                                >
                                                    {{ $task->project->name }}
                                                </a>

                                            @else
                                                <span class="text-muted">
                                                    —
                                                </span>
                                            @endif

                                        </td>

                                        {{-- Assignee --}}
                                        <td>

                                            @if($task->assignee)

                                                <div class="d-flex align-items-center gap-2">

                                                    <div
                                                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px;"
                                                    >
                                                        {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                    </div>

                                                    <span>
                                                        {{ $task->assignee->name }}
                                                    </span>

                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    Unassigned
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Status --}}
                                        <td>

                                            @php
                                                $statusClasses = [
                                                    'todo' => 'bg-secondary',
                                                    'in_progress' => 'bg-primary',
                                                    'completed' => 'bg-success',
                                                    'blocked' => 'bg-danger',
                                                ];

                                                $statusLabels = [
                                                    'todo' => 'To Do',
                                                    'in_progress' => 'In Progress',
                                                    'completed' => 'Completed',
                                                    'blocked' => 'Blocked',
                                                ];
                                            @endphp

                                            <span class="badge {{ $statusClasses[$task->status] ?? 'bg-secondary' }}">
                                                {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                                            </span>

                                        </td>

                                        {{-- Priority --}}
                                        <td>

                                            @php
                                                $priorityClasses = [
                                                    'low' => 'bg-success',
                                                    'medium' => 'bg-warning text-dark',
                                                    'high' => 'bg-danger',
                                                ];
                                            @endphp

                                            <span class="badge {{ $priorityClasses[$task->priority] ?? 'bg-secondary' }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>

                                        </td>

                                        {{-- Due date --}}
                                        <td>

                                            @if($task->due_date)

                                                <span
                                                    class="{{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-danger fw-semibold' : '' }}"
                                                >
                                                    {{ $task->due_date->format('d M Y') }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end px-4">

                                            <a
                                                href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="bi bi-eye"></i>
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="p-4">
                        {{ $tasks->links() }}
                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">
                            <i class="bi bi-check2-square fs-1 text-muted"></i>
                        </div>

                        <h5>
                            No tasks found
                        </h5>

                        <p class="text-muted mb-0">
                            There are no tasks matching your current filters.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>
</x-taskflow-layout>