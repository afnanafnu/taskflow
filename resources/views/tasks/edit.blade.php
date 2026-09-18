@push('styles')
    @vite('resources/css/tasks/task-create.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        Edit Task
    </x-slot>

    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <a
                        href="{{ route('projects.tasks.index', $project) }}"
                        class="text-decoration-none text-muted"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Tasks
                    </a>

                    <span class="text-muted">/</span>

                    <span class="text-muted">
                        {{ $project->name }}
                    </span>

                </div>

                <h2 class="mb-1">
                    Edit Task
                </h2>

                <p class="text-muted mb-0">
                    Update the details of
                    <strong>{{ $task->title }}</strong>.
                </p>

            </div>

            <a
                href="{{ route('projects.tasks.show', [$project, $task]) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Task
            </a>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="alert alert-danger shadow-sm">

                <div class="fw-semibold mb-2">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    Please fix the following errors:

                </div>

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="row g-4">

            {{-- Main Form --}}
            <div class="col-xl-8 col-lg-8">

                <div class="card border-0 shadow-sm">

                    {{-- Card Header --}}
                    <div class="card-header bg-white border-0 p-4">

                        <h5 class="mb-1">

                            <i class="bi bi-pencil-square me-2"></i>

                            Task Details

                        </h5>

                        <p class="text-muted small mb-0">

                            Update the information for this task.

                        </p>

                    </div>


                    {{-- Card Body --}}
                    <div class="card-body p-4">

                        <form
                            method="POST"
                            action="{{ route('projects.tasks.update', [$project, $task]) }}"
                        >

                            @csrf

                            @method('PUT')


                            {{-- Title --}}
                            <div class="mb-4">

                                <label
                                    for="title"
                                    class="form-label fw-semibold"
                                >
                                    Task Title
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $task->title) }}"
                                    placeholder="Enter task title"
                                    maxlength="150"
                                    required
                                    autofocus
                                >

                                @error('title')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="mb-4">

                                <label
                                    for="description"
                                    class="form-label fw-semibold"
                                >
                                    Description
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="6"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Describe the task..."
                                    maxlength="5000"
                                >{{ old('description', $task->description) }}</textarea>

                                @error('description')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text">
                                    Maximum 5000 characters.
                                </div>

                            </div>


                            {{-- Task Settings --}}
                            <div class="row">

                                {{-- Status --}}
                                <div class="col-md-6 mb-4">

                                    <label
                                        for="status"
                                        class="form-label fw-semibold"
                                    >
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        id="status"
                                        name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required
                                    >

                                        <option
                                            value="todo"
                                            @selected(old('status', $task->status) === 'todo')
                                        >
                                            To Do
                                        </option>

                                        <option
                                            value="in_progress"
                                            @selected(old('status', $task->status) === 'in_progress')
                                        >
                                            In Progress
                                        </option>

                                        <option
                                            value="completed"
                                            @selected(old('status', $task->status) === 'completed')
                                        >
                                            Completed
                                        </option>

                                        <option
                                            value="blocked"
                                            @selected(old('status', $task->status) === 'blocked')
                                        >
                                            Blocked
                                        </option>

                                    </select>

                                    @error('status')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Priority --}}
                                <div class="col-md-6 mb-4">

                                    <label
                                        for="priority"
                                        class="form-label fw-semibold"
                                    >
                                        Priority
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        id="priority"
                                        name="priority"
                                        class="form-select @error('priority') is-invalid @enderror"
                                        required
                                    >

                                        <option
                                            value="low"
                                            @selected(old('priority', $task->priority) === 'low')
                                        >
                                            Low
                                        </option>

                                        <option
                                            value="medium"
                                            @selected(old('priority', $task->priority) === 'medium')
                                        >
                                            Medium
                                        </option>

                                        <option
                                            value="high"
                                            @selected(old('priority', $task->priority) === 'high')
                                        >
                                            High
                                        </option>

                                    </select>

                                    @error('priority')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>


                            {{-- Assignment + Due Date --}}
                            <div class="row">

                                {{-- Assignee --}}
                                <div class="col-md-6 mb-4">

                                    <label
                                        for="assignee_id"
                                        class="form-label fw-semibold"
                                    >
                                        Assign To
                                    </label>

                                    <select
                                        id="assignee_id"
                                        name="assignee_id"
                                        class="form-select @error('assignee_id') is-invalid @enderror"
                                    >

                                        <option value="">
                                            Unassigned
                                        </option>

                                        @foreach($members as $member)

                                            <option
                                                value="{{ $member->id }}"
                                                @selected(
                                                    (string) old(
                                                        'assignee_id',
                                                        $task->assignee_id
                                                    ) === (string) $member->id
                                                )
                                            >
                                                {{ $member->name }}
                                                ({{ $member->email }})
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('assignee_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <div class="form-text">
                                        Only project members can be assigned.
                                    </div>

                                </div>


                                {{-- Due Date --}}
                                <div class="col-md-6 mb-4">

                                    <label
                                        for="due_date"
                                        class="form-label fw-semibold"
                                    >
                                        Due Date
                                    </label>

                                    <input
                                        type="date"
                                        id="due_date"
                                        name="due_date"
                                        class="form-control @error('due_date') is-invalid @enderror"
                                        value="{{ old(
                                            'due_date',
                                            $task->due_date?->format('Y-m-d')
                                        ) }}"
                                    >

                                    @error('due_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top">

                                <a
                                    href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                    class="btn btn-outline-secondary"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-check-lg me-1"></i>
                                    Update Task
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- Side Information --}}
            <div class="col-xl-4 col-lg-4">

                {{-- Project Information --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 p-4">

                        <h6 class="mb-0">

                            <i class="bi bi-info-circle me-2"></i>

                            Project

                        </h6>

                    </div>


                    <div class="card-body p-4">

                        <h5 class="mb-2">
                            {{ $project->name }}
                        </h5>

                        @if($project->description)

                            <p class="text-muted small mb-3">
                                {{ $project->description }}
                            </p>

                        @endif

                        <div class="d-flex align-items-center gap-2 text-muted small">

                            <i class="bi bi-people"></i>

                            <span>

                                {{ $members->count() }}

                                {{ Str::plural('member', $members->count()) }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Current Task Information --}}
                <div class="card border-0 shadow-sm mt-3">

                    <div class="card-header bg-white border-0 p-4">

                        <h6 class="mb-0">

                            <i class="bi bi-check2-square me-2"></i>

                            Current Task

                        </h6>

                    </div>

                    <div class="card-body p-4">

                        <div class="mb-3">

                            <div class="small text-muted mb-1">
                                Task
                            </div>

                            <div class="fw-semibold">
                                {{ $task->title }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <div class="small text-muted mb-1">
                                Status
                            </div>

                            @php
                                $statusLabels = [
                                    'todo' => 'To Do',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'blocked' => 'Blocked',
                                ];

                                $statusClasses = [
                                    'todo' => 'bg-secondary',
                                    'in_progress' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'blocked' => 'bg-danger',
                                ];
                            @endphp

                            <span class="badge {{ $statusClasses[$task->status] ?? 'bg-secondary' }}">
                                {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                            </span>

                        </div>

                        <div>

                            <div class="small text-muted mb-1">
                                Priority
                            </div>

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

                        </div>

                    </div>

                </div>


                {{-- Edit Tips --}}
                <div class="card border-0 shadow-sm mt-3">

                    <div class="card-body p-4">

                        <h6 class="mb-3">

                            <i class="bi bi-lightbulb me-2"></i>

                            Edit Tips

                        </h6>

                        <ul class="small text-muted mb-0 ps-3">

                            <li class="mb-2">
                                Keep the task title clear and specific.
                            </li>

                            <li class="mb-2">
                                Update the description when requirements change.
                            </li>

                            <li class="mb-2">
                                Review the priority if the importance changes.
                            </li>

                            <li>
                                Update the due date when the deadline changes.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>