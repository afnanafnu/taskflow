<x-taskflow-layout>

    <x-slot name="title">
        {{ $task->title }}
    </x-slot>

    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <a href="{{ route('projects.tasks.index', $task->project) }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left"></i>
                        Tasks
                    </a>

                    <span class="text-muted">/</span>

                    <span class="text-muted">
                        {{ $task->project->name }}
                    </span>

                </div>

                <h2 class="mb-1">
                    {{ $task->title }}
                </h2>

                <p class="text-muted mb-0">
                    Project:
                    <strong>{{ $task->project->name }}</strong>
                </p>

            </div>


            {{-- Actions --}}
            <div class="d-flex gap-2">

                @can('update', $task)
                    <a href="{{ route('projects.tasks.edit', [$task->project, $task]) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Edit
                    </a>
                @endcan


                @can('delete', $task)
                    <form action="{{ route('projects.tasks.destroy', [$task->project, $task]) }}" method="POST"
                        onsubmit="return confirm('Delete this task?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>
                            Delete
                        </button>

                    </form>
                @endcan

            </div>

        </div>


        {{-- Task Details --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 p-4">

                <h5 class="mb-1">
                    <i class="bi bi-check2-square me-2"></i>
                    Task Details
                </h5>

                <p class="text-muted small mb-0">
                    Task information and assignment details.
                </p>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- Description --}}
                    <div class="col-lg-7">

                        <p class="fw-semibold mb-2">
                            Description
                        </p>

                        <div class="text-muted">

                            @if ($task->description)
                                {!! nl2br(e($task->description)) !!}
                            @else
                                No description.
                            @endif

                        </div>

                    </div>


                    {{-- Task Information --}}
                    <div class="col-lg-5">

                        <div class="mb-3">

                            <span class="fw-semibold">
                                Status:
                            </span>

                            <span class="badge bg-secondary ms-1">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>

                        </div>


                        <div class="mb-3">

                            <span class="fw-semibold">
                                Priority:
                            </span>

                            <span class="badge bg-secondary ms-1">
                                {{ ucfirst($task->priority) }}
                            </span>

                        </div>


                        <div class="mb-3">

                            <span class="fw-semibold">
                                Assignee:
                            </span>

                            <span class="text-muted">
                                {{ $task->assignee->name ?? 'Unassigned' }}
                            </span>

                        </div>


                        <div>

                            <span class="fw-semibold">
                                Due Date:
                            </span>

                            <span class="text-muted">
                                {{ $task->due_date?->format('d M Y') ?? '-' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Comments --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Comments
                    </h5>

                    <span class="badge bg-light text-dark">
                        {{ $task->comments->count() }}
                    </span>

                </div>

                <hr>


                {{-- Comment List --}}
                @forelse($task->comments as $comment)
                    <div class="border rounded p-3 mb-3" x-data="{
                        editing: false,
                        body: @js($comment->body)
                    }">

                        {{-- Normal Comment --}}
                        <div x-show="!editing">

                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">

                                <strong>
                                    {{ $comment->user->name }}
                                </strong>

                                <small class="text-muted">
                                    {{ $comment->created_at->format('d M Y H:i') }}
                                </small>

                            </div>


                            <p class="mt-2 mb-2">
                                {{ $comment->body }}
                            </p>


                            {{-- Comment Actions --}}
                            <div class="d-flex gap-2">

                                @can('update', $comment)
                                    <button type="button" class="btn btn-sm btn-outline-primary" @click="editing = true">
                                        <i class="bi bi-pencil me-1"></i>
                                        Edit
                                    </button>
                                @endcan


                                @can('delete', $comment)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this comment?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>
                                            Delete
                                        </button>

                                    </form>
                                @endcan

                            </div>

                        </div>


                        {{-- Edit Comment --}}
                        <div x-show="editing" x-cloak>

                            <form action="{{ route('comments.update', $comment) }}" method="POST">

                                @csrf
                                @method('PUT')


                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Edit Comment
                                    </label>

                                    <textarea name="body" class="form-control" rows="4" maxlength="5000" required x-model="body"></textarea>

                                </div>


                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-lg me-1"></i>
                                        Save Changes
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        @click="editing = false">
                                        Cancel
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-4">

                        <i class="bi bi-chat-square-text fs-2 text-muted"></i>

                        <p class="text-muted mb-0 mt-2">
                            No comments yet.
                        </p>

                    </div>
                @endforelse


                {{-- Add Comment --}}
                @can('create', [App\Models\Comment::class, $task])
                    <div class="border-top pt-4 mt-4">

                        <h6 class="mb-3">
                            Add Comment
                        </h6>

                        <form action="{{ route('tasks.comments.store', $task) }}" method="POST">

                            @csrf

                            <div class="mb-3">

                                <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="4"
                                    placeholder="Write your comment..." maxlength="5000" required>{{ old('body') }}</textarea>

                                @error('body')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>
                                Add Comment
                            </button>

                        </form>

                    </div>
                @endcan

            </div>

        </div>

    </div>

</x-taskflow-layout>
