@push('styles')
    @vite('resources/css/tasks/task-show.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        {{ $task->title }}
    </x-slot>

    @php
        $statusConfig = [
            'todo' => [
                'label' => 'To Do',
                'class' => 'task-status-todo',
                'icon' => 'bi-circle',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'class' => 'task-status-progress',
                'icon' => 'bi-arrow-repeat',
            ],
            'completed' => [
                'label' => 'Completed',
                'class' => 'task-status-completed',
                'icon' => 'bi-check-circle',
            ],
            'blocked' => [
                'label' => 'Blocked',
                'class' => 'task-status-blocked',
                'icon' => 'bi-exclamation-octagon',
            ],
        ];

        $priorityConfig = [
            'low' => [
                'label' => 'Low',
                'class' => 'task-priority-low',
                'icon' => 'bi-arrow-down',
            ],
            'medium' => [
                'label' => 'Medium',
                'class' => 'task-priority-medium',
                'icon' => 'bi-dash',
            ],
            'high' => [
                'label' => 'High',
                'class' => 'task-priority-high',
                'icon' => 'bi-arrow-up',
            ],
        ];

        $currentStatus = $statusConfig[$task->status] ?? [
            'label' => ucfirst(str_replace('_', ' ', $task->status)),
            'class' => 'task-status-todo',
            'icon' => 'bi-circle',
        ];

        $currentPriority = $priorityConfig[$task->priority] ?? [
            'label' => ucfirst($task->priority),
            'class' => 'task-priority-medium',
            'icon' => 'bi-dash',
        ];

        $isOverdue = $task->due_date
            && $task->due_date->isPast()
            && $task->status !== 'completed';
    @endphp


    <div class="container-fluid py-4 task-show-page">

        {{-- =====================================================
            Header
        ====================================================== --}}
        <div class="task-show-header">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4">

                <div>

                    {{-- Breadcrumb --}}
                    <div class="task-show-breadcrumb">

                        <a href="{{ route('projects.tasks.index', $task->project) }}">

                            <i class="bi bi-arrow-left"></i>

                            Tasks

                        </a>

                        <span class="text-muted">/</span>

                        <a href="{{ route('projects.show', $task->project) }}">
                            {{ $task->project->name }}
                        </a>

                        <span class="text-muted">/</span>

                        <span class="text-muted">
                            Task
                        </span>

                    </div>


                    {{-- Task Title --}}
                    <div class="task-show-title-wrapper">

                        <div class="task-show-icon">
                            <i class="bi bi-check2-square fs-4"></i>
                        </div>

                        <div>

                            <h2 class="task-show-title">
                                {{ $task->title }}
                            </h2>

                            <div class="task-show-project">

                                <i class="bi bi-folder2 me-1"></i>

                                {{ $task->project->name }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="task-show-actions">

                    @can('update', $task)

                        <a
                            href="{{ route('projects.tasks.edit', [$task->project, $task]) }}"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-pencil me-1"></i>
                            Edit
                        </a>

                    @endcan


                    @can('delete', $task)

                        <form
                            action="{{ route('projects.tasks.destroy', [$task->project, $task]) }}"
                            method="POST"
                            onsubmit="return confirm('Delete this task?')"
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

        </div>


        {{-- =====================================================
            Task Summary Cards
        ====================================================== --}}
        <div class="row g-3 mb-4">

            {{-- Status --}}
            <div class="col-md-6 col-xl-3">

                <div class="task-info-card">

                    <div class="task-info-card-body">

                        <div class="task-info-label">

                            <span>
                                Status
                            </span>

                            <i class="bi bi-activity"></i>

                        </div>


                        @can('changeStatus', $task)

                            <form
                                action="{{ route('projects.tasks.status', [$task->project, $task]) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <select
                                    name="status"
                                    class="form-select task-status-select"
                                    onchange="this.form.submit()"
                                >

                                    @foreach($statusConfig as $value => $status)

                                        <option
                                            value="{{ $value }}"
                                            @selected($task->status === $value)
                                        >
                                            {{ $status['label'] }}
                                        </option>

                                    @endforeach

                                </select>

                            </form>

                        @else

                            <span class="task-status {{ $currentStatus['class'] }}">

                                <i class="bi {{ $currentStatus['icon'] }}"></i>

                                {{ $currentStatus['label'] }}

                            </span>

                        @endcan

                    </div>

                </div>

            </div>


            {{-- Priority --}}
            <div class="col-md-6 col-xl-3">

                <div class="task-info-card">

                    <div class="task-info-card-body">

                        <div class="task-info-label">

                            <span>
                                Priority
                            </span>

                            <i class="bi bi-flag"></i>

                        </div>

                        <span class="task-priority {{ $currentPriority['class'] }}">

                            <i class="bi {{ $currentPriority['icon'] }}"></i>

                            {{ $currentPriority['label'] }}

                        </span>

                    </div>

                </div>

            </div>


            {{-- Assignee --}}
            <div class="col-md-6 col-xl-3">

                <div class="task-info-card">

                    <div class="task-info-card-body">

                        <div class="task-info-label">

                            <span>
                                Assigned To
                            </span>

                            <i class="bi bi-person"></i>

                        </div>

                        @if($task->assignee)

                            <div class="task-assignee">

                                <div class="task-avatar">

                                    {{ strtoupper(substr($task->assignee->name, 0, 1)) }}

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

                            <div class="text-muted small">

                                <i class="bi bi-person-dash me-1"></i>

                                Unassigned

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Due Date --}}
            <div class="col-md-6 col-xl-3">

                <div class="task-info-card">

                    <div class="task-info-card-body">

                        <div class="task-info-label">

                            <span>
                                Due Date
                            </span>

                            <i class="bi bi-calendar3"></i>

                        </div>

                        @if($task->due_date)

                            <div class="task-due-date {{ $isOverdue ? 'overdue' : '' }}">

                                <i class="bi bi-calendar-event me-1"></i>

                                {{ $task->due_date->format('d M Y') }}

                            </div>

                            @if($isOverdue)

                                <span class="task-overdue">

                                    <i class="bi bi-exclamation-circle me-1"></i>

                                    Overdue

                                </span>

                            @endif

                        @else

                            <span class="text-muted small">
                                No due date
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Main Content
        ====================================================== --}}
        <div class="row g-4">


            {{-- =================================================
                Left Column
            ================================================== --}}
            <div class="col-xl-8">


                {{-- Description --}}
                <div class="task-content-card mb-4">

                    <div class="task-content-header">

                        <h5 class="mb-1">

                            <i class="bi bi-file-text me-2"></i>

                            Description

                        </h5>

                        <p>
                            Details and requirements for this task.
                        </p>

                    </div>


                    <div class="task-content-body">

                        @if($task->description)

                            <div class="task-description">

                                {!! nl2br(e($task->description)) !!}

                            </div>

                        @else

                            <div class="task-empty">

                                <div class="task-empty-icon">

                                    <i class="bi bi-file-earmark-text"></i>

                                </div>

                                <div>
                                    No description has been added.
                                </div>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Labels --}}
                @if($task->labels->count())

                    <div class="task-content-card mb-4">

                        <div class="task-content-header">

                            <h5 class="mb-1">

                                <i class="bi bi-tags me-2"></i>

                                Labels

                            </h5>

                            <p>
                                Labels associated with this task.
                            </p>

                        </div>

                        <div class="task-content-body">

                            <div class="task-labels">

                                @foreach($task->labels as $label)

                                    <span
                                        class="task-label"
                                        style="background-color: {{ $label->colour }};"
                                    >

                                        <i class="bi bi-tag"></i>

                                        {{ $label->name }}

                                    </span>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endif


                {{-- Comments --}}
                <div class="task-content-card">

                    <div class="task-content-header">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div>

                                <h5 class="mb-1">

                                    <i class="bi bi-chat-left-text me-2"></i>

                                    Comments

                                </h5>

                                <p>
                                    Discussion related to this task.
                                </p>

                            </div>

                            <span class="badge bg-light text-dark border px-3 py-2">

                                {{ $task->comments->count() }}

                                {{ Str::plural('comment', $task->comments->count()) }}

                            </span>

                        </div>

                    </div>


                    <div class="task-content-body">


                        {{-- Comment List --}}
                        @forelse($task->comments as $comment)

                            <div
                                class="task-comment"
                                x-data="{
                                    editing: false,
                                    body: @js($comment->body)
                                }"
                            >

                                {{-- Normal Comment --}}
                                <div x-show="!editing">

                                    <div class="task-comment-header">

                                        <div class="task-comment-user">

                                            <div class="task-comment-avatar">

                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}

                                            </div>

                                            <div>

                                                <div class="task-comment-name">

                                                    {{ $comment->user->name }}

                                                </div>

                                                <div class="task-comment-date">

                                                    {{ $comment->created_at->format('d M Y, H:i') }}

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="task-comment-body">

                                        {{ $comment->body }}

                                    </div>


                                    {{-- Comment Actions --}}
                                    <div class="d-flex gap-2">

                                        @can('update', $comment)

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                @click="editing = true"
                                            >

                                                <i class="bi bi-pencil me-1"></i>

                                                Edit

                                            </button>

                                        @endcan


                                        @can('delete', $comment)

                                            <form
                                                action="{{ route('comments.destroy', $comment) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this comment?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >

                                                    <i class="bi bi-trash me-1"></i>

                                                    Delete

                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </div>


                                {{-- Edit Comment --}}
                                <div x-show="editing" x-cloak>

                                    <form
                                        action="{{ route('comments.update', $comment) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">

                                                Edit Comment

                                            </label>

                                            <textarea
                                                name="body"
                                                class="form-control"
                                                rows="4"
                                                maxlength="5000"
                                                required
                                                x-model="body"
                                            ></textarea>

                                        </div>


                                        <div class="d-flex gap-2">

                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-sm"
                                            >

                                                <i class="bi bi-check-lg me-1"></i>

                                                Save Changes

                                            </button>


                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary btn-sm"
                                                @click="editing = false"
                                            >

                                                Cancel

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        @empty

                            <div class="task-empty">

                                <div class="task-empty-icon">

                                    <i class="bi bi-chat-square-text"></i>

                                </div>

                                <h6 class="mb-1">
                                    No comments yet
                                </h6>

                                <div>
                                    Start the discussion by adding a comment.
                                </div>

                            </div>

                        @endforelse


                        {{-- Add Comment --}}
                        @can('create', [App\Models\Comment::class, $task])

                            <div class="border-top pt-4 mt-4">

                                <h6 class="fw-semibold mb-3">

                                    <i class="bi bi-plus-circle me-1"></i>

                                    Add Comment

                                </h6>

                                <form
                                    action="{{ route('tasks.comments.store', $task) }}"
                                    method="POST"
                                >

                                    @csrf

                                    <div class="mb-3">

                                        <textarea
                                            name="body"
                                            class="form-control @error('body') is-invalid @enderror"
                                            rows="4"
                                            placeholder="Write your comment..."
                                            maxlength="5000"
                                            required
                                        >{{ old('body') }}</textarea>

                                        @error('body')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i class="bi bi-send me-1"></i>

                                        Add Comment

                                    </button>

                                </form>

                            </div>

                        @endcan

                    </div>

                </div>

            </div>


            {{-- =================================================
                Right Column
            ================================================== --}}
            <div class="col-xl-4">


                {{-- Project --}}
                <div class="task-content-card mb-4">

                    <div class="task-content-header">

                        <h6 class="mb-0">

                            <i class="bi bi-folder me-2"></i>

                            Project

                        </h6>

                    </div>


                    <div class="task-project-card">

                        <div class="task-project-name">

                            {{ $task->project->name }}

                        </div>


                        @if($task->project->description)

                            <div class="task-project-description mb-3">

                                {{ $task->project->description }}

                            </div>

                        @endif


                        <a
                            href="{{ route('projects.show', $task->project) }}"
                            class="btn btn-outline-primary btn-sm"
                        >

                            <i class="bi bi-arrow-right me-1"></i>

                            View Project

                        </a>

                    </div>

                </div>


                {{-- Task Information --}}
                <div class="task-content-card mb-4">

                    <div class="task-content-header">

                        <h6 class="mb-0">

                            <i class="bi bi-info-circle me-2"></i>

                            Task Information

                        </h6>

                    </div>


                    <div class="task-content-body">

                        <div class="task-meta-row">

                            <span class="task-meta-label">
                                Created
                            </span>

                            <span class="task-meta-value">
                                {{ $task->created_at->format('d M Y') }}
                            </span>

                        </div>


                        <div class="task-meta-row">

                            <span class="task-meta-label">
                                Last Updated
                            </span>

                            <span class="task-meta-value">
                                {{ $task->updated_at->format('d M Y') }}
                            </span>

                        </div>


                        <div class="task-meta-row">

                            <span class="task-meta-label">
                                Comments
                            </span>

                            <span class="task-meta-value">
                                {{ $task->comments->count() }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Quick Actions --}}
                <div class="task-content-card">

                    <div class="task-content-header">

                        <h6 class="mb-0">

                            <i class="bi bi-lightning-charge me-2"></i>

                            Quick Actions

                        </h6>

                    </div>


                    <div class="task-content-body">

                        <div class="task-quick-actions">

                            @can('update', $task)

                                <a
                                    href="{{ route('projects.tasks.edit', [$task->project, $task]) }}"
                                    class="btn btn-outline-primary"
                                >

                                    <i class="bi bi-pencil me-1"></i>

                                    Edit Task

                                </a>

                            @endcan


                            <a
                                href="{{ route('projects.tasks.index', $task->project) }}"
                                class="btn btn-outline-secondary"
                            >

                                <i class="bi bi-list-check me-1"></i>

                                Back to Tasks

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>