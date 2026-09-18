
<x-taskflow-layout>

    <x-slot name="title">
        {{ $project->name }}
    </x-slot>

    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <a
                        href="{{ route('projects.index') }}"
                        class="text-decoration-none text-muted"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Projects
                    </a>

                    <span class="text-muted">/</span>

                    <span class="text-muted">
                        {{ $project->name }}
                    </span>

                </div>

                <h2 class="mb-1">
                    {{ $project->name }}
                </h2>

                @if($project->description)

                    <p class="text-muted mb-0">
                        {{ $project->description }}
                    </p>

                @else

                    <p class="text-muted mb-0">
                        No project description.
                    </p>

                @endif

            </div>


            {{-- Project Actions --}}
            <div class="d-flex gap-2">

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
                            class="btn btn-danger"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Delete
                        </button>

                    </form>

                @endcan

            </div>

        </div>


        <div class="row g-4">

            {{-- Project Information --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 p-4">

                        <h5 class="mb-0">

                            <i class="bi bi-folder me-2"></i>

                            Project Information

                        </h5>

                    </div>


                    <div class="card-body p-4">

                        <div class="mb-3">

                            <div class="small text-muted mb-1">
                                Owner
                            </div>

                            <div class="fw-semibold">
                                {{ $project->owner->name ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="small text-muted mb-1">
                                Status
                            </div>

                            <span class="badge bg-secondary">
                                {{ ucfirst($project->status) }}
                            </span>

                        </div>


                        <div class="mb-3">

                            <div class="small text-muted mb-1">
                                Members
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-people me-1"></i>

                                {{ $project->users->count() }}

                            </div>

                        </div>


                        <div>

                            <div class="small text-muted mb-1">
                                Tasks
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-check2-square me-1"></i>

                                {{ $project->tasks->count() }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Tasks --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 p-4">

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">

                            <h5 class="mb-0">

                                <i class="bi bi-list-check me-2"></i>

                                Tasks

                            </h5>


                            @can('create', [App\Models\Task::class, $project])

                                <a
                                    href="{{ route('projects.tasks.create', $project) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Add Task
                                </a>

                            @endcan

                        </div>

                    </div>


                    <div class="card-body p-4">

                        @forelse($project->tasks as $task)

                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">

                                    <a
                                        href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                        class="fw-bold text-decoration-none"
                                    >
                                        {{ $task->title }}
                                    </a>


                                    <span class="badge bg-secondary align-self-start">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>

                                </div>


                                <div class="small text-muted mt-2">

                                    <span>
                                        <strong>Priority:</strong>
                                        {{ ucfirst($task->priority) }}
                                    </span>


                                    @if($task->due_date)

                                        <span class="ms-2">
                                            <strong>Due:</strong>
                                            {{ $task->due_date->format('d M Y') }}
                                        </span>

                                    @endif


                                    @if($task->assignee)

                                        <span class="ms-2">

                                            <strong>Assigned:</strong>
                                            {{ $task->assignee->name }}

                                        </span>

                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-4">

                                <i class="bi bi-check2-square fs-2 text-muted"></i>

                                <p class="text-muted mb-0 mt-2">
                                    No tasks found.
                                </p>

                                @can('create', [App\Models\Task::class, $project])

                                    <a
                                        href="{{ route('projects.tasks.create', $project) }}"
                                        class="btn btn-sm btn-primary mt-3"
                                    >
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Create First Task
                                    </a>

                                @endcan

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>
