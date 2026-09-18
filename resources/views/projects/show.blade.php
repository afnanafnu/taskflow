
@push('styles')
    @vite('resources/css/projects/project-show.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        {{ $project->name }}
    </x-slot>

    <div class="container-fluid py-4 project-show-page">

        {{-- Header --}}
        <div class="project-show-header">

            <div class="project-show-header-main">

                <div class="project-show-breadcrumb">

                    <a href="{{ route('projects.index') }}">
                        <i class="bi bi-arrow-left"></i>
                        Projects
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        {{ $project->name }}
                    </span>

                </div>

                <div class="project-show-title-wrapper">

                    <div class="project-show-icon">
                        <i class="bi bi-folder-fill"></i>
                    </div>

                    <div>

                        <h2 class="project-show-title">
                            {{ $project->name }}
                        </h2>

                        <p class="project-show-description">
                            {{ $project->description ?: 'No project description.' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="project-show-actions">

                @can('update', $project)

                    <a
                        href="{{ route('projects.edit', $project) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-pencil me-1"></i>
                        Edit
                    </a>

                @endcan


                @can('delete', $project)

                    <form
                        action="{{ route('projects.destroy', $project) }}"
                        method="POST"
                        onsubmit="return confirm('Delete this project?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Delete
                        </button>

                    </form>

                @endcan

            </div>

        </div>


        {{-- Statistics --}}
        <div class="row g-3 mb-4">

            {{-- Status --}}
            <div class="col-6 col-xl-3">

                <div class="project-stat-card">

                    <div class="project-stat-icon status">
                        <i class="bi bi-activity"></i>
                    </div>

                    <div>

                        <div class="project-stat-label">
                            Status
                        </div>

                        @if($project->status === 'active')

                            <span class="project-show-status project-show-status-active">
                                <i class="bi bi-play-circle-fill"></i>
                                Active
                            </span>

                        @elseif($project->status === 'completed')

                            <span class="project-show-status project-show-status-completed">
                                <i class="bi bi-check-circle-fill"></i>
                                Completed
                            </span>

                        @elseif($project->status === 'archived')

                            <span class="project-show-status project-show-status-archived">
                                <i class="bi bi-archive-fill"></i>
                                Archived
                            </span>

                        @else

                            <span class="project-show-status project-show-status-default">
                                <i class="bi bi-circle-fill"></i>
                                {{ ucfirst($project->status) }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Members --}}
            <div class="col-6 col-xl-3">

                <div class="project-stat-card">

                    <div class="project-stat-icon members">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>

                        <div class="project-stat-label">
                            Members
                        </div>

                        <div class="project-stat-value">
                            {{ $project->users->count() }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Tasks --}}
            <div class="col-6 col-xl-3">

                <div class="project-stat-card">

                    <div class="project-stat-icon tasks">
                        <i class="bi bi-check2-square"></i>
                    </div>

                    <div>

                        <div class="project-stat-label">
                            Tasks
                        </div>

                        <div class="project-stat-value">
                            {{ $project->tasks->count() }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Owner --}}
            <div class="col-6 col-xl-3">

                <div class="project-stat-card">

                    <div class="project-stat-icon owner">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div>

                        <div class="project-stat-label">
                            Owner
                        </div>

                        <div class="project-owner-stat">
                            {{ $project->owner->name ?? 'N/A' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Main Content --}}
        <div class="row g-4">


            {{-- Project Information --}}
            <div class="col-lg-4">

                <div class="project-show-card">

                    <div class="project-show-card-header">

                        <div>

                            <h5>
                                <i class="bi bi-folder2-open me-2"></i>
                                Project Information
                            </h5>

                            <p>
                                Overview of this project.
                            </p>

                        </div>

                    </div>


                    <div class="project-show-card-body">


                        {{-- Owner --}}
                        <div class="project-info-item">

                            <div class="project-info-label">
                                <i class="bi bi-person"></i>
                                Owner
                            </div>

                            <div class="project-owner">

                                <div class="project-owner-avatar">

                                    {{ strtoupper(
                                        substr(
                                            $project->owner->name ?? 'N',
                                            0,
                                            1
                                        )
                                    ) }}

                                </div>

                                <div>

                                    <div class="project-owner-name">
                                        {{ $project->owner->name ?? 'N/A' }}
                                    </div>

                                    @if($project->owner)

                                        <div class="project-owner-email">
                                            {{ $project->owner->email }}
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="project-info-item">

                            <div class="project-info-label">
                                <i class="bi bi-activity"></i>
                                Status
                            </div>

                            @if($project->status === 'active')

                                <span class="project-show-status project-show-status-active">
                                    <i class="bi bi-play-circle-fill"></i>
                                    Active
                                </span>

                            @elseif($project->status === 'completed')

                                <span class="project-show-status project-show-status-completed">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Completed
                                </span>

                            @elseif($project->status === 'archived')

                                <span class="project-show-status project-show-status-archived">
                                    <i class="bi bi-archive-fill"></i>
                                    Archived
                                </span>

                            @else

                                <span class="project-show-status project-show-status-default">
                                    <i class="bi bi-circle-fill"></i>
                                    {{ ucfirst($project->status) }}
                                </span>

                            @endif

                        </div>


                        {{-- Members --}}
                        <div class="project-info-item">

                            <div class="project-info-label">
                                <i class="bi bi-people"></i>
                                Members
                            </div>

                            <strong class="project-info-value">

                                {{ $project->users->count() }}

                                {{ Str::plural('member', $project->users->count()) }}

                            </strong>

                        </div>


                        {{-- Tasks --}}
                        <div class="project-info-item">

                            <div class="project-info-label">
                                <i class="bi bi-check2-square"></i>
                                Tasks
                            </div>

                            <strong class="project-info-value">

                                {{ $project->tasks->count() }}

                                {{ Str::plural('task', $project->tasks->count()) }}

                            </strong>

                        </div>


                        {{-- Created --}}
                        <div class="project-info-item">

                            <div class="project-info-label">
                                <i class="bi bi-calendar3"></i>
                                Created
                            </div>

                            <strong class="project-info-value">
                                {{ $project->created_at->format('d M Y') }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Members --}}
                <div class="project-show-card mt-4">

                    <div class="project-show-card-header">

                        <div>

                            <h5>
                                <i class="bi bi-people me-2"></i>
                                Members
                            </h5>

                            <p>
                                People assigned to this project.
                            </p>

                        </div>

                    </div>


                    <div class="project-show-card-body">

                        @forelse($project->users as $member)

                            <div class="project-member">

                                <div class="project-member-avatar">

                                    {{ strtoupper(
                                        substr($member->name, 0, 1)
                                    ) }}

                                </div>

                                <div class="project-member-info">

                                    <div class="project-member-name">
                                        {{ $member->name }}
                                    </div>

                                    <div class="project-member-email">
                                        {{ $member->email }}
                                    </div>

                                </div>

                                <span class="project-member-role">

                                    {{ ucfirst($member->pivot->role ?? 'member') }}

                                </span>

                            </div>

                        @empty

                            <div class="project-small-empty">

                                <i class="bi bi-people"></i>

                                <span>
                                    No members assigned.
                                </span>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- Tasks --}}
            <div class="col-lg-8">

                <div class="project-show-card">

                    <div class="project-show-card-header">

                        <div>

                            <h5>
                                <i class="bi bi-list-check me-2"></i>
                                Tasks
                            </h5>

                            <p>
                                Tasks associated with this project.
                            </p>

                        </div>


                        @can('create', [App\Models\Task::class, $project])

                            <a
                                href="{{ route('projects.tasks.create', $project) }}"
                                class="btn btn-primary btn-sm"
                            >
                                <i class="bi bi-plus-lg me-1"></i>
                                Add Task
                            </a>

                        @endcan

                    </div>


                    <div class="project-show-card-body project-tasks-body">

                        @forelse($project->tasks as $task)

                            <div class="project-task">

                                <div class="project-task-main">

                                    <div class="project-task-icon">
                                        <i class="bi bi-check2-square"></i>
                                    </div>

                                    <div class="project-task-content">

                                        <a
                                            href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                            class="project-task-title"
                                        >
                                            {{ $task->title }}
                                        </a>


                                        <div class="project-task-meta">

                                            {{-- Priority --}}
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

                                                <span class="task-priority task-priority-medium">
                                                    <i class="bi bi-flag-fill"></i>
                                                    {{ ucfirst($task->priority) }}
                                                </span>

                                            @endif


                                            {{-- Due Date --}}
                                            @if($task->due_date)

                                                @php
                                                    $taskOverdue =
                                                        $task->due_date->isPast()
                                                        && $task->status !== 'completed';
                                                @endphp

                                                <span class="project-task-due {{ $taskOverdue ? 'overdue' : '' }}">

                                                    <i class="bi bi-calendar3"></i>

                                                    {{ $task->due_date->format('d M Y') }}

                                                    @if($taskOverdue)
                                                        <span>Overdue</span>
                                                    @endif

                                                </span>

                                            @endif


                                            {{-- Assignee --}}
                                            @if($task->assignee)

                                                <span class="project-task-assignee">

                                                    <i class="bi bi-person"></i>

                                                    {{ $task->assignee->name }}

                                                </span>

                                            @else

                                                <span class="project-task-assignee">

                                                    <i class="bi bi-person-dash"></i>

                                                    Unassigned

                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                                <div class="project-task-right">

                                    {{-- Task Status --}}
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
                                            <i class="bi bi-check-circle"></i>
                                            Completed
                                        </span>

                                    @elseif($task->status === 'blocked')

                                        <span class="task-status task-status-blocked">
                                            <i class="bi bi-exclamation-octagon"></i>
                                            Blocked
                                        </span>

                                    @else

                                        <span class="task-status task-status-todo">
                                            <i class="bi bi-circle"></i>
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>

                                    @endif


                                    <a
                                        href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                        class="project-task-view"
                                        title="View Task"
                                    >
                                        <i class="bi bi-chevron-right"></i>
                                    </a>

                                </div>

                            </div>

                        @empty

                            <div class="project-tasks-empty">

                                <div class="project-tasks-empty-icon">
                                    <i class="bi bi-check2-square"></i>
                                </div>

                                <h6>
                                    No tasks yet
                                </h6>

                                <p>
                                    Create a task to start working on this project.
                                </p>

                                @can('create', [App\Models\Task::class, $project])

                                    <a
                                        href="{{ route('projects.tasks.create', $project) }}"
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Create First Task
                                    </a>

                                @endcan

                            </div>

                        @endforelse

                    </div>


                    @if($project->tasks->count() > 0)

                        <div class="project-tasks-footer">

                            <a
                                href="{{ route('projects.tasks.index', $project) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                View All Tasks
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>