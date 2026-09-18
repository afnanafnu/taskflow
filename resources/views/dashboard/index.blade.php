<x-taskflow-layout>

    <x-slot name="title">
        Dashboard
    </x-slot>

    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="mb-4">

            <h2 class="mb-1">
                Dashboard
            </h2>

            <p class="text-muted mb-0">
                Welcome back, {{ auth()->user()->name }}.
            </p>

        </div>


        {{-- Admin Information --}}
        @if(auth()->user()->isAdmin())

            <div class="alert alert-dark border-0 shadow-sm">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-shield-check fs-5"></i>

                    <strong>
                        Administrator
                    </strong>

                </div>

                <div class="small mt-1">
                    You can access and manage all projects and tasks.
                </div>

            </div>

        @endif


        {{-- Statistics --}}
        <div class="row g-4">

            {{-- Projects --}}
            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <div class="text-muted small">
                                    {{ auth()->user()->isAdmin()
                                        ? 'All Projects'
                                        : 'My Projects'
                                    }}
                                </div>

                                <h2 class="mt-2 mb-0">
                                    {{ $projectsCount }}
                                </h2>

                            </div>

                            <div class="text-primary fs-3">
                                <i class="bi bi-folder-fill"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Tasks --}}
            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <div class="text-muted small">
                                    {{ auth()->user()->isAdmin()
                                        ? 'All Tasks'
                                        : 'My Tasks'
                                    }}
                                </div>

                                <h2 class="mt-2 mb-0">
                                    {{ $tasksCount }}
                                </h2>

                            </div>

                            <div class="text-primary fs-3">
                                <i class="bi bi-check2-square"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Completed --}}
            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <div class="text-muted small">
                                    Completed
                                </div>

                                <h2 class="mt-2 mb-0 text-success">
                                    {{ $completedTasks }}
                                </h2>

                            </div>

                            <div class="text-success fs-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Pending --}}
            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <div class="text-muted small">
                                    Pending
                                </div>

                                <h2 class="mt-2 mb-0 text-warning">
                                    {{ $pendingTasks }}
                                </h2>

                            </div>

                            <div class="text-warning fs-3">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header bg-white border-0 p-4">

                <h5 class="mb-1">
                    <i class="bi bi-lightning-charge me-2"></i>
                    Quick Actions
                </h5>

                <p class="text-muted small mb-0">
                    Quickly access your project workspace.
                </p>

            </div>

            <div class="card-body px-4 pb-4">

                <div class="d-flex gap-2 flex-wrap">

                    <a
                        href="{{ route('projects.index') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-folder me-1"></i>
                        View Projects
                    </a>


                    @can('create', App\Models\Project::class)

                        <a
                            href="{{ route('projects.create') }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-folder-plus me-1"></i>
                            Create Project
                        </a>

                    @endcan

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>