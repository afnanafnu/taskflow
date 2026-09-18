<x-taskflow-layout>

    <x-slot name="title">
        Edit Project
    </x-slot>

    <div class="container-fluid py-4">

        <div class="row justify-content-center">

            <div class="col-xl-8 col-lg-9">

                <div class="card border-0 shadow-sm">

                    {{-- Header --}}
                    <div class="card-header bg-white border-0 p-4">

                        <div class="d-flex align-items-center gap-2 mb-2">

                            <a
                                href="{{ route('projects.show', $project) }}"
                                class="text-decoration-none text-muted"
                            >
                                <i class="bi bi-arrow-left me-1"></i>
                                Back to Project
                            </a>

                        </div>

                        <h3 class="mb-1">
                            Edit Project
                        </h3>

                        <p class="text-muted mb-0">
                            Update project details and manage project members.
                        </p>

                    </div>


                    {{-- Form --}}
                    <div class="card-body p-4">

                        @if($errors->any())

                            <div class="alert alert-danger">

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


                        <form
                            action="{{ route('projects.update', $project) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PUT')


                            {{-- Project Name --}}
                            <div class="mb-4">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Project Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $project->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter project name"
                                    maxlength="150"
                                    required
                                    autofocus
                                >

                                @error('name')

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
                                    placeholder="Describe the project..."
                                    maxlength="5000"
                                >{{ old('description', $project->description) }}</textarea>

                                @error('description')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text">
                                    Maximum 5000 characters.
                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="mb-4">

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
                                        value="active"
                                        @selected(old('status', $project->status) === 'active')
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="completed"
                                        @selected(old('status', $project->status) === 'completed')
                                    >
                                        Completed
                                    </option>

                                    <option
                                        value="archived"
                                        @selected(old('status', $project->status) === 'archived')
                                    >
                                        Archived
                                    </option>

                                </select>

                                @error('status')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Project Members --}}
                            <div
                                class="mb-4"
                                x-data="{
                                    open: false,
                                    search: '',
                                    selected: @js(
                                        old(
                                            'members',
                                            $project->users
                                                ->where('id', '!=', $project->owner_id)
                                                ->pluck('id')
                                                ->map(fn ($id) => (string) $id)
                                                ->values()
                                        )
                                    ),

                                    users: @js(
                                        $users->map(fn ($user) => [
                                            'id' => (string) $user->id,
                                            'name' => $user->name,
                                            'email' => $user->email,
                                        ])->values()
                                    ),

                                    get filteredUsers() {
                                        const term = this.search
                                            .toLowerCase()
                                            .trim();

                                        if (!term) {
                                            return this.users;
                                        }

                                        return this.users.filter(user =>
                                            user.name.toLowerCase().includes(term) ||
                                            user.email.toLowerCase().includes(term)
                                        );
                                    },

                                    isSelected(id) {
                                        return this.selected.includes(String(id));
                                    },

                                    toggleUser(id) {
                                        id = String(id);

                                        if (this.isSelected(id)) {
                                            this.selected = this.selected.filter(
                                                selectedId => selectedId !== id
                                            );
                                        } else {
                                            this.selected.push(id);
                                        }
                                    },

                                    removeUser(id) {
                                        this.selected = this.selected.filter(
                                            selectedId => selectedId !== String(id)
                                        );
                                    },

                                    get selectedUsers() {
                                        return this.users.filter(user =>
                                            this.selected.includes(String(user.id))
                                        );
                                    }
                                }"
                            >

                                <label class="form-label fw-semibold">
                                    Project Members
                                </label>


                                {{-- Hidden inputs --}}
                                <template
                                    x-for="userId in selected"
                                    :key="userId"
                                >

                                    <input
                                        type="hidden"
                                        name="members[]"
                                        :value="userId"
                                    >

                                </template>


                                {{-- Select Button --}}
                                <div class="position-relative">

                                    <button
                                        type="button"
                                        class="form-select text-start"
                                        @click="open = !open"
                                        style="min-height: 42px;"
                                    >

                                        <span
                                            x-show="selected.length === 0"
                                            class="text-muted"
                                        >
                                            Select project members
                                        </span>

                                        <span
                                            x-show="selected.length > 0"
                                            x-text="selected.length + ' member(s) selected'"
                                        ></span>

                                    </button>


                                    {{-- Dropdown --}}
                                    <div
                                        x-show="open"
                                        x-transition
                                        @click.outside="open = false"
                                        class="position-absolute bg-white border rounded shadow-sm w-100 mt-1"
                                        style="z-index: 1050; max-height: 320px; overflow-y: auto;"
                                        x-cloak
                                    >

                                        {{-- Search --}}
                                        <div class="p-2 border-bottom sticky-top bg-white">

                                            <div class="input-group">

                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-search"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Search users..."
                                                    x-model="search"
                                                    @click.stop
                                                >

                                            </div>

                                        </div>


                                        {{-- Users --}}
                                        <div>

                                            <template
                                                x-for="user in filteredUsers"
                                                :key="user.id"
                                            >

                                                <button
                                                    type="button"
                                                    class="w-100 border-0 bg-white text-start px-3 py-2"
                                                    @click="toggleUser(user.id)"
                                                >

                                                    <div class="d-flex align-items-center">

                                                        {{-- Checkbox --}}
                                                        <div class="me-3">

                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input"
                                                                :checked="isSelected(user.id)"
                                                                @click.stop
                                                                @change="toggleUser(user.id)"
                                                            >

                                                        </div>


                                                        {{-- Avatar --}}
                                                        <div
                                                            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                            style="width: 36px; height: 36px;"
                                                            x-text="user.name.charAt(0).toUpperCase()"
                                                        ></div>


                                                        {{-- User Info --}}
                                                        <div>

                                                            <div
                                                                class="fw-semibold"
                                                                x-text="user.name"
                                                            ></div>

                                                            <small
                                                                class="text-muted"
                                                                x-text="user.email"
                                                            ></small>

                                                        </div>

                                                    </div>

                                                </button>

                                            </template>


                                            {{-- Empty --}}
                                            <div
                                                x-show="filteredUsers.length === 0"
                                                class="text-center text-muted py-4"
                                            >
                                                No users found.
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Selected Users --}}
                                <div
                                    class="d-flex flex-wrap gap-2 mt-2"
                                    x-show="selectedUsers.length > 0"
                                >

                                    <template
                                        x-for="user in selectedUsers"
                                        :key="user.id"
                                    >

                                        <span class="badge bg-light text-dark border px-3 py-2">

                                            <span x-text="user.name"></span>

                                            <button
                                                type="button"
                                                class="btn-close ms-2"
                                                style="font-size: 9px;"
                                                @click="removeUser(user.id)"
                                                aria-label="Remove"
                                            ></button>

                                        </span>

                                    </template>

                                </div>


                                @error('members')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                                @error('members.*')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div class="form-text">
                                    Select the users who should have access to this project.
                                    The project owner is always retained as manager.
                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top">

                                <a
                                    href="{{ route('projects.show', $project) }}"
                                    class="btn btn-outline-secondary"
                                >
                                    <i class="bi bi-x-lg me-1"></i>
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-check-lg me-1"></i>
                                    Update Project
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>