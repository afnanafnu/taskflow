@push('styles')
    @vite('resources/css/users/users-index.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        Users
    </x-slot>

    <div class="container-fluid py-4 users-page">

        <div class="users-page-header">

            <div>

                <div class="users-breadcrumb">
                    <i class="bi bi-shield-check"></i>
                    <span>Administration</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>Users</span>
                </div>

                <div class="users-title-wrapper">

                    <div class="users-title-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>
                        <h2 class="users-title">
                            User Management
                        </h2>

                        <p class="users-subtitle">
                            Manage TaskFlow users and their access roles.
                        </p>
                    </div>

                </div>

            </div>

            @can('create', App\Models\User::class)

                <a
                    href="{{ route('users.create') }}"
                    class="btn btn-primary users-add-btn"
                >
                    <i class="bi bi-person-plus-fill me-1"></i>
                    Add User
                </a>

            @endcan

        </div>


        <div
            class="users-table-card"
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

                    if (!term) {
                        return this.rows;
                    }

                    return this.rows.filter(row =>
                        row.innerText.toLowerCase().includes(term)
                    );
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

            <div class="users-table-header">

                <div>
                    <h5>
                        <i class="bi bi-people me-2"></i>
                        All Users
                    </h5>

                    <p>
                        Manage registered users and their roles.
                    </p>
                </div>

                <div class="users-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        placeholder="Search users..."
                        x-model="search"
                    >

                    <button
                        type="button"
                        x-show="search"
                        x-cloak
                        @click="search = ''"
                    >
                        <i class="bi bi-x"></i>
                    </button>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table users-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Projects</th>
                            <th>Tasks</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody x-ref="tableBody">

                        @forelse($users as $user)

                            <tr data-row>

                                <td>
                                    <span class="user-id">
                                        #{{ $user->id }}
                                    </span>
                                </td>

                                <td>

                                    <div class="user-info">

                                        <div class="user-avatar">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>

                                            <div class="user-name">
                                                {{ $user->name }}
                                            </div>

                                            @if($user->id === auth()->id())
                                                <span class="user-you">
                                                    You
                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </td>

                                <td>
                                    <span class="user-email">
                                        {{ $user->email }}
                                    </span>
                                </td>

                                <td>

                                    @if($user->isAdmin())

                                        <span class="user-role user-role-admin">
                                            <i class="bi bi-shield-fill-check"></i>
                                            Administrator
                                        </span>

                                    @else

                                        <span class="user-role user-role-user">
                                            <i class="bi bi-person-fill"></i>
                                            User
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    <span class="user-stat">
                                        <i class="bi bi-folder-fill"></i>
                                        {{ $user->owned_projects_count }}
                                    </span>
                                </td>

                                <td>
                                    <span class="user-stat">
                                        <i class="bi bi-check2-square"></i>
                                        {{ $user->assigned_tasks_count }}
                                    </span>
                                </td>

                                <td>
                                    <span class="user-date">
                                        {{ $user->created_at->format('d M Y') }}
                                    </span>
                                </td>

                                <td>

                                    <div class="user-actions">

                                        @can('update', $user)

                                            <a
                                                href="{{ route('users.edit', $user) }}"
                                                class="user-action-btn user-action-edit"
                                                title="Edit User"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        @endcan

                                        @can('delete', $user)

                                            <form
                                                action="{{ route('users.destroy', $user) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this user?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="user-action-btn user-action-delete"
                                                    title="Delete User"
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
                                <td colspan="8">

                                    <div class="users-empty">

                                        <i class="bi bi-people"></i>

                                        <h6>
                                            No users found
                                        </h6>

                                        <p>
                                            There are no users in the system yet.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse


                        <tr
                            x-show="filteredRows.length === 0 && rows.length > 0"
                            x-cloak
                        >

                            <td colspan="8">

                                <div class="users-empty">

                                    <i class="bi bi-search"></i>

                                    <h6>
                                        No matching users
                                    </h6>

                                    <p>
                                        Try a different search term.
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


            <div class="users-table-footer">

                <div class="users-result-count">
                    Showing
                    <strong x-text="startItem"></strong>
                    -
                    <strong x-text="endItem"></strong>
                    of
                    <strong x-text="filteredRows.length"></strong>
                    users
                </div>

                <div class="users-pagination">

                    <button
                        type="button"
                        class="users-page-btn"
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
                            class="users-page-number"
                            :class="{ 'active': page === pageNumber }"
                            @click="goToPage(pageNumber)"
                            x-text="pageNumber"
                        ></button>

                    </template>

                    <button
                        type="button"
                        class="users-page-btn"
                        @click="nextPage()"
                        :disabled="page === totalPages"
                    >
                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>