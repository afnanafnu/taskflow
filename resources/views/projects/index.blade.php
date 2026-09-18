
<x-taskflow-layout>

    <x-slot name="title">
        Projects
    </x-slot>

    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h2 class="mb-1">
                    Projects
                </h2>

                <p class="text-muted mb-0">
                    @if(auth()->user()->isAdmin())
                        All projects
                    @else
                        Projects you have access to
                    @endif
                </p>
            </div>

            @can('create', App\Models\Project::class)

                <a
                    href="{{ route('projects.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Create Project
                </a>

            @endcan

        </div>


        {{-- Projects Table --}}
        <div
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

            <div class="card border-0 shadow-sm">

                {{-- Table Header --}}
                <div class="card-header bg-white border-0 p-3">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                        <div>
                            <h5 class="mb-0">
                                Projects
                            </h5>
                        </div>


                        {{-- Search --}}
                        <div class="data-table-search">

                            <div class="input-group">

                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search projects..."
                                    x-model="search"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Table --}}
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Project</th>

                                <th>Owner</th>

                                <th>Status</th>

                                <th>Members</th>

                                <th>Tasks</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody x-ref="tableBody">

                            @forelse($projects as $project)

                                <tr data-row>

                                    {{-- ID --}}
                                    <td>
                                        {{ $project->id }}
                                    </td>


                                    {{-- Project --}}
                                    <td>

                                        <strong>
                                            {{ $project->name }}
                                        </strong>

                                        @if($project->description)

                                            <div class="small text-muted">
                                                {{ Str::limit($project->description, 60) }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Owner --}}
                                    <td>
                                        {{ $project->owner->name ?? 'N/A' }}
                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($project->status) }}
                                        </span>

                                    </td>


                                    {{-- Members --}}
                                    <td>
                                        {{ $project->users_count }}
                                    </td>


                                    {{-- Tasks --}}
                                    <td>
                                        {{ $project->tasks_count }}
                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- VIEW --}}
                                            @can('view', $project)

                                                <a
                                                    href="{{ route('projects.show', $project) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="View"
                                                >
                                                    <i class="bi bi-eye"></i>

                                                    <span class="d-none d-xl-inline">
                                                        View
                                                    </span>
                                                </a>

                                            @endcan


                                            {{-- EDIT --}}
                                            @can('update', $project)

                                                <a
                                                    href="{{ route('projects.edit', $project) }}"
                                                    class="btn btn-sm btn-primary"
                                                    title="Edit"
                                                >
                                                    <i class="bi bi-pencil"></i>

                                                    <span class="d-none d-xl-inline">
                                                        Edit
                                                    </span>
                                                </a>

                                            @endcan


                                            {{-- DELETE --}}
                                            @can('delete', $project)

                                                <form
                                                    action="{{ route('projects.destroy', $project) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this project?')"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete"
                                                    >
                                                        <i class="bi bi-trash"></i>

                                                        <span class="d-none d-xl-inline">
                                                            Delete
                                                        </span>
                                                    </button>

                                                </form>

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center py-5"
                                    >

                                        <div class="text-muted">

                                            <i class="bi bi-folder-x fs-2 d-block mb-2"></i>

                                            No projects found.

                                        </div>

                                    </td>

                                </tr>

                            @endforelse


                            {{-- Alpine empty search result --}}
                            <tr x-show="filteredRows.length === 0">

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="bi bi-search fs-2 d-block mb-2"></i>

                                        No matching projects found.

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                {{-- Footer --}}
                <div class="card-footer bg-white border-0 p-3">

                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">


                        {{-- Result Count --}}
                        <div class="small text-muted">

                            Showing

                            <strong x-text="startItem"></strong>

                            -

                            <strong x-text="endItem"></strong>

                            of

                            <strong x-text="filteredRows.length"></strong>

                            results

                        </div>


                        {{-- Per Page --}}
                        <div class="d-flex align-items-center gap-2">

                            <label class="small text-muted mb-0">
                                Show
                            </label>

                            <select
                                class="form-select form-select-sm"
                                style="width: 75px"
                                x-model.number="perPage"
                            >

                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>

                            </select>

                        </div>


                        {{-- Pagination --}}
                        <div class="d-flex align-items-center gap-1">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary"
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
                                    class="btn btn-sm"
                                    :class="
                                        page === pageNumber
                                            ? 'btn-primary'
                                            : 'btn-outline-secondary'
                                    "
                                    @click="goToPage(pageNumber)"
                                    x-text="pageNumber"
                                ></button>

                            </template>


                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary"
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

    </div>

</x-taskflow-layout>
