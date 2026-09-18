
@push('styles')
    @vite('resources/css/tasks/tasks-index.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        {{ isset($project) ? $project->name . ' Tasks' : 'All Tasks' }}
    </x-slot>


    <div class="container-fluid py-4 tasks-index-page">

        {{-- =====================================================
            Header
        ====================================================== --}}
        <div class="tasks-index-header">

            <div class="tasks-index-header-main">

                <div class="tasks-index-breadcrumb">

                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        Dashboard
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    @if(isset($project))

                        <a href="{{ route('projects.index') }}">
                            Projects
                        </a>

                        <i class="bi bi-chevron-right"></i>

                        <span>
                            {{ $project->name }}
                        </span>

                    @else

                        <span>
                            Tasks
                        </span>

                    @endif

                </div>


                <div class="tasks-index-title-wrapper">

                    <div class="tasks-index-title-icon">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <div>

                        <h2 class="tasks-index-title">
                            {{ isset($project) ? $project->name . ' Tasks' : 'All Tasks' }}
                        </h2>

                        <p class="tasks-index-subtitle">

                            {{ isset($project)
                                ? 'Manage tasks for this project.'
                                : 'View and manage your accessible tasks.'
                            }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- Create Task --}}
            @isset($project)

                @can('create', [App\Models\Task::class, $project])

                    <a
                        href="{{ route('projects.tasks.create', $project) }}"
                        class="btn btn-primary tasks-create-btn"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Create Task
                    </a>

                @endcan

            @endisset

        </div>


        {{-- =====================================================
            Filters
        ====================================================== --}}
        <div class="tasks-filter-card">

            <div class="tasks-filter-header">

                <div>

                    <h5>
                        <i class="bi bi-funnel me-2"></i>
                        Filter Tasks
                    </h5>

                    <p>
                        Narrow down the task list using the available filters.
                    </p>

                </div>

            </div>


            <div class="tasks-filter-body">

                <form
                    method="GET"
                    action="{{ isset($project)
                        ? route('projects.tasks.index', $project)
                        : route('tasks.index')
                    }}"
                >

                    <div class="row g-3">

                        {{-- Search --}}
                        <div class="col-12 col-lg-4">

                            <label class="tasks-filter-label">
                                Search
                            </label>

                            <div class="tasks-search-input">

                                <i class="bi bi-search"></i>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search tasks..."
                                >

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-6 col-lg-2">

                            <label class="tasks-filter-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select tasks-filter-select"
                            >

                                <option value="">
                                    All Status
                                </option>

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


                        {{-- Priority --}}
                        <div class="col-6 col-lg-2">

                            <label class="tasks-filter-label">
                                Priority
                            </label>

                            <select
                                name="priority"
                                class="form-select tasks-filter-select"
                            >

                                <option value="">
                                    All Priority
                                </option>

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


                        {{-- Filter --}}
                        <div class="col-6 col-lg-2 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary tasks-filter-btn w-100"
                            >
                                <i class="bi bi-search me-1"></i>
                                Filter
                            </button>

                        </div>


                        {{-- Reset --}}
                        <div class="col-6 col-lg-2 d-flex align-items-end">

                            <a
                                href="{{ isset($project)
                                    ? route('projects.tasks.index', $project)
                                    : route('tasks.index')
                                }}"
                                class="btn btn-outline-secondary tasks-reset-btn w-100"
                            >
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
            Tasks Table
        ====================================================== --}}
        <div class="tasks-table-card">

            {{-- Table Header --}}
            <div class="tasks-table-header">

                <div>

                    <h5 class="tasks-table-title">
                        <i class="bi bi-check2-square me-2"></i>
                        Tasks
                    </h5>

                    <p class="tasks-table-description">

                        Showing
                        <strong>{{ $tasks->total() }}</strong>
                        {{ Str::plural('task', $tasks->total()) }}

                    </p>

                </div>


                {{-- Active Filters --}}
                @if(
                    request('search')
                    || request('status')
                    || request('priority')
                )

                    <div class="tasks-active-filter">

                        <i class="bi bi-funnel-fill"></i>

                        Filters applied

                    </div>

                @endif

            </div>


            {{-- Table --}}
            <div class="table-responsive">

                @if($tasks->count())

                    <table class="table tasks-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="tasks-col-task">
                                    Task
                                </th>

                                @if(!isset($project))

                                    <th>
                                        Project
                                    </th>

                                @endif

                                <th>
                                    Assignee
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th class="tasks-col-actions">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($tasks as $task)

                                <tr>

                                    {{-- Task --}}
                                    <td>

                                        <div class="task-table-main">

                                            <div class="task-table-icon">
                                                <i class="bi bi-check2-square"></i>
                                            </div>

                                            <div class="task-table-content">

                                                <a
                                                    href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                    class="task-table-title"
                                                >
                                                    {{ $task->title }}
                                                </a>

                                                @if($task->description)

                                                    <div class="task-table-description">

                                                        {{ Str::limit(
                                                            $task->description,
                                                            80
                                                        ) }}

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Project --}}
                                    @if(!isset($project))

                                        <td>

                                            @if($task->project)

                                                <a
                                                    href="{{ route('projects.show', $task->project) }}"
                                                    class="task-project-link"
                                                >

                                                    <span class="task-project-icon">
                                                        <i class="bi bi-folder-fill"></i>
                                                    </span>

                                                    {{ $task->project->name }}

                                                </a>

                                            @else

                                                <span class="task-empty-value">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                    @endif


                                    {{-- Assignee --}}
                                    <td>

                                        @if($task->assignee)

                                            <div class="task-assignee">

                                                <div class="task-assignee-avatar">

                                                    {{ strtoupper(
                                                        substr(
                                                            $task->assignee->name,
                                                            0,
                                                            1
                                                        )
                                                    ) }}

                                                </div>

                                                <div>

                                                    <div class="task-assignee-name">
                                                        {{ $task->assignee->name }}
                                                    </div>

                                                    <div class="task-assignee-email">
                                                        {{ $task->assignee->email }}
                                                    </div>

                                                </div>

                                            </div>

                                        @else

                                            <div class="task-unassigned">

                                                <span class="task-unassigned-icon">
                                                    <i class="bi bi-person-dash"></i>
                                                </span>

                                                <span>
                                                    Unassigned
                                                </span>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($task->status === 'todo')

                                            <span class="task-status task-status-todo">
                                                <i class="bi bi-circle"></i>
                                                To Do
                                            </span>

                                        @elseif($task->status === 'in_progress')

                                            <span class="task-status task-status-progress">
                                                <i class="bi bi-arrow-repeat"></i>
                                                In Progress
                                            </span>

                                        @elseif($task->status === 'completed')

                                            <span class="task-status task-status-completed">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Completed
                                            </span>

                                        @elseif($task->status === 'blocked')

                                            <span class="task-status task-status-blocked">
                                                <i class="bi bi-exclamation-octagon-fill"></i>
                                                Blocked
                                            </span>

                                        @else

                                            <span class="task-status task-status-default">
                                                <i class="bi bi-circle"></i>
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Priority --}}
                                    <td>

                                        @if($task->priority === 'low')

                                            <span class="task-priority task-priority-low">
                                                <i class="bi bi-flag-fill"></i>
                                                Low
                                            </span>

                                        @elseif($task->priority === 'medium')

                                            <span class="task-priority task-priority-medium">
                                                <i class="bi bi-flag-fill"></i>
                                                Medium
                                            </span>

                                        @elseif($task->priority === 'high')

                                            <span class="task-priority task-priority-high">
                                                <i class="bi bi-flag-fill"></i>
                                                High
                                            </span>

                                        @else

                                            <span class="task-priority task-priority-default">
                                                <i class="bi bi-flag-fill"></i>
                                                {{ ucfirst($task->priority) }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Due Date --}}
                                    <td>

                                        @if($task->due_date)

                                            @if(
                                                $task->due_date->isPast()
                                                && $task->status !== 'completed'
                                            )

                                                <div class="task-due overdue">

                                                    <i class="bi bi-calendar-x"></i>

                                                    <div>

                                                        <div>
                                                            {{ $task->due_date->format('d M Y') }}
                                                        </div>

                                                        <small>
                                                            Overdue
                                                        </small>

                                                    </div>

                                                </div>

                                            @else

                                                <div class="task-due">

                                                    <i class="bi bi-calendar3"></i>

                                                    {{ $task->due_date->format('d M Y') }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="task-empty-value">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="task-actions">

                                            @can('view', $task)

                                                <a
                                                    href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                    class="task-action-btn task-action-view"
                                                    title="View Task"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                            @endcan


                                            @can('update', $task)

                                                <a
                                                    href="{{ route('projects.tasks.edit', [$task->project, $task]) }}"
                                                    class="task-action-btn task-action-edit"
                                                    title="Edit Task"
                                                >
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    {{-- Empty --}}
                    <div class="tasks-empty">

                        <div class="tasks-empty-icon">
                            <i class="bi bi-check2-square"></i>
                        </div>

                        <h5>
                            No tasks found
                        </h5>

                        <p>
                            There are no tasks matching your current filters.
                        </p>

                        @if(
                            request('search')
                            || request('status')
                            || request('priority')
                        )

                            <a
                                href="{{ isset($project)
                                    ? route('projects.tasks.index', $project)
                                    : route('tasks.index')
                                }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Clear Filters
                            </a>

                        @elseif(isset($project))

                            @can('create', [App\Models\Task::class, $project])

                                <a
                                    href="{{ route('projects.tasks.create', $project) }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Create First Task
                                </a>

                            @endcan

                        @endif

                    </div>

                @endif

            </div>


            {{-- Pagination --}}
            @if($tasks->hasPages())

                <div class="tasks-pagination">

                    <div class="tasks-pagination-info">

                        Showing

                        <strong>
                            {{ $tasks->firstItem() ?? 0 }}
                        </strong>

                        -

                        <strong>
                            {{ $tasks->lastItem() ?? 0 }}
                        </strong>

                        of

                        <strong>
                            {{ $tasks->total() }}
                        </strong>

                    </div>


                    <div>

                        {{ $tasks->links() }}

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-taskflow-layout>