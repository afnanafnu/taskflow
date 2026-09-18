@push('styles')
    @vite('resources/css/projects/projects-index.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        Projects
    </x-slot>

    <div class="container-fluid py-4 projects-page">

        {{-- Page Header --}}
        <div class="projects-page-header">

            <div>
                <div class="projects-breadcrumb">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Workspace</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>Projects</span>
                </div>

                <div class="d-flex align-items-center gap-3">

                    <div class="projects-title-icon">
                        <i class="bi bi-folder-fill"></i>
                    </div>

                    <div>
                        <h2 class="projects-title">
                            Projects
                        </h2>

                        <p class="projects-subtitle">
                            @if(auth()->user()->isAdmin())
                                Manage and monitor all projects
                            @else
                                Projects you have access to
                            @endif
                        </p>
                    </div>

                </div>
            </div>

            @can('create', App\Models\Project::class)

                <a
                    href="{{ route('projects.create') }}"
                    class="btn btn-primary projects-create-btn"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Create Project
                </a>

            @endcan

        </div>


        {{-- Projects Table --}}
        <div
            class="projects-table-card"
            x-data="{
                search: '',
                page: 1,
                perPage: 10,

                get rows() {
                    return Array.from(
                        this.$refs.tableBody.querySelectorAll('tr[data-row]')
                    );
                },

                get filteredRows() {
                    const term = this.search.toLowerCase().trim();

                    return this.rows.filter(row => {
                        if (!term) {
                            return true;
                        }

                        return row.innerText
                            .toLowerCase()
                            .includes(term);
                    });
                },

                get totalPages() {
                    return Math.max(
                        1,
                        Math.ceil(this.filteredRows.length / this.perPage)
                    );
                },

                get visibleRows() {
                    const start = (this.page - 1) * this.perPage;

                    return this.filteredRows.slice(
                        start,
                        start + this.perPage
                    );
                },

                get startItem() {
                    if (this.filteredRows.length === 0) {
                        return 0;
                    }

                    return ((this.page - 1) * this.perPage) + 1;
                },

                get endItem() {
                    return Math.min(
                        this.page * this.perPage,
                        this.filteredRows.length
                    );
                },

                updateRows() {
                    this.rows.forEach(row => {
                        row.style.display =
                            this.visibleRows.includes(row)
                                ? ''
                                : 'none';
                    });
                },

                resetPage() {
                    this.page = 1;

                    this.$nextTick(() => {
                        this.updateRows();
                    });
                },

                previousPage() {
                    if (this.page > 1) {
                        this.page--;

                        this.$nextTick(() => {
                            this.updateRows();
                        });
                    }
                },

                nextPage() {
                    if (this.page < this.totalPages) {
                        this.page++;

                        this.$nextTick(() => {
                            this.updateRows();
                        });
                    }
                },

                goToPage(page) {
                    this.page = page;

                    this.$nextTick(() => {
                        this.updateRows();
                    });
                },

                init() {
                    this.updateRows();

                    this.$watch('search', () => {
                        this.resetPage();
                    });

                    this.$watch('perPage', () => {
                        this.resetPage();
                    });
                }
            }"
        >

            {{-- Table Header --}}
            <div class="projects-table-header">

                <div>
                    <h5 class="projects-table-title">
                        <i class="bi bi-folder2-open me-2"></i>
                        All Projects
                    </h5>

                    <p class="projects-table-description">
                        Browse and manage your available projects.
                    </p>
                </div>


                {{-- Search --}}
                <div class="projects-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        placeholder="Search projects..."
                        x-model="search"
                    >

                    <button
                        type="button"
                        x-show="search"
                        x-cloak
                        @click="search = ''"
                        class="projects-search-clear"
                    >
                        <i class="bi bi-x"></i>
                    </button>

                </div>

            </div>


            {{-- Table --}}
            <div class="table-responsive">

                <table class="table projects-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th class="projects-col-id">
                                #
                            </th>

                            <th>
                                Project
                            </th>

                            <th>
                                Owner
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Members
                            </th>

                            <th>
                                Tasks
                            </th>

                            <th class="projects-col-actions">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody x-ref="tableBody">

                        @forelse($projects as $project)

                            <tr data-row>

                                {{-- ID --}}
                                <td>

                                    <span class="project-id">
                                        #{{ $project->id }}
                                    </span>

                                </td>


                                {{-- Project --}}
                                <td>

                                    <div class="project-name-wrapper">

                                        <div class="project-avatar">

                                            <i class="bi bi-folder-fill"></i>

                                        </div>

                                        <div>

                                            <a
                                                href="{{ route('projects.show', $project) }}"
                                                class="project-name"
                                            >
                                                {{ $project->name }}
                                            </a>

                                            @if($project->description)

                                                <div class="project-description">

                                                    {{ Str::limit($project->description, 70) }}

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Owner --}}
                                <td>

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

                                        <span>
                                            {{ $project->owner->name ?? 'N/A' }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php
                                        $statusClass = match($project->status) {
                                            'active' => 'project-status-active',
                                            'completed' => 'project-status-completed',
                                            'archived' => 'project-status-archived',
                                            default => 'project-status-default',
                                        };
                                    @endphp

                                    <span class="project-status {{ $statusClass }}">

                                        @if($project->status === 'active')
                                            <i class="bi bi-play-circle-fill"></i>
                                        @elseif($project->status === 'completed')
                                            <i class="bi bi-check-circle-fill"></i>
                                        @elseif($project->status === 'archived')
                                            <i class="bi bi-archive-fill"></i>
                                        @else
                                            <i class="bi bi-circle-fill"></i>
                                        @endif

                                        {{ ucfirst($project->status) }}

                                    </span>

                                </td>


                                {{-- Members --}}
                                <td>

                                    <div class="project-stat">

                                        <span class="project-stat-icon members">
                                            <i class="bi bi-people-fill"></i>
                                        </span>

                                        <strong>
                                            {{ $project->users_count }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- Tasks --}}
                                <td>

                                    <div class="project-stat">

                                        <span class="project-stat-icon tasks">
                                            <i class="bi bi-check2-square"></i>
                                        </span>

                                        <strong>
                                            {{ $project->tasks_count }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="project-actions">

                                        @can('view', $project)

                                            <a
                                                href="{{ route('projects.show', $project) }}"
                                                class="project-action-btn project-action-view"
                                                title="View Project"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                        @endcan


                                        @can('update', $project)

                                            <a
                                                href="{{ route('projects.edit', $project) }}"
                                                class="project-action-btn project-action-edit"
                                                title="Edit Project"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        @endcan


                                        @can('delete', $project)

                                            <form
                                                action="{{ route('projects.destroy', $project) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this project?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="project-action-btn project-action-delete"
                                                    title="Delete Project"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="projects-empty">

                                        <div class="projects-empty-icon">

                                            <i class="bi bi-folder-x"></i>

                                        </div>

                                        <h6>
                                            No projects found
                                        </h6>

                                        <p>
                                            There are no projects available yet.
                                        </p>

                                        @can('create', App\Models\Project::class)

                                            <a
                                                href="{{ route('projects.create') }}"
                                                class="btn btn-primary btn-sm"
                                            >
                                                <i class="bi bi-plus-lg me-1"></i>
                                                Create Project
                                            </a>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforelse


                        {{-- No search results --}}
                        <tr
                            x-show="filteredRows.length === 0 && rows.length > 0"
                            x-cloak
                        >

                            <td colspan="7">

                                <div class="projects-empty">

                                    <div class="projects-empty-icon">

                                        <i class="bi bi-search"></i>

                                    </div>

                                    <h6>
                                        No matching projects
                                    </h6>

                                    <p>
                                        Try searching with a different project name.
                                    </p>

                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        @click="search = ''"
                                    >
                                        Clear Search
                                    </button>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- Footer --}}
            <div class="projects-table-footer">

                <div class="projects-result-count">

                    Showing

                    <strong x-text="startItem"></strong>

                    -

                    <strong x-text="endItem"></strong>

                    of

                    <strong x-text="filteredRows.length"></strong>

                    projects

                </div>


                <div class="projects-footer-controls">

                    {{-- Per Page --}}
                    <div class="projects-per-page">

                        <label>
                            Show
                        </label>

                        <select
                            x-model.number="perPage"
                            class="form-select form-select-sm"
                        >
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>

                    </div>


                    {{-- Pagination --}}
                    <div class="projects-pagination">

                        <button
                            type="button"
                            class="projects-page-btn"
                            @click="previousPage()"
                            :disabled="page === 1"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </button>


                        <template
                            x-for="pageNumber in totalPages"
                            :key="pageNumber"
                        >

                            <button
                                type="button"
                                class="projects-page-number"
                                :class="{ 'active': page === pageNumber }"
                                @click="goToPage(pageNumber)"
                                x-text="pageNumber"
                            ></button>

                        </template>


                        <button
                            type="button"
                            class="projects-page-btn"
                            @click="nextPage()"
                            :disabled="page === totalPages"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>