
@push('styles')
  @vite('resources/css/projects/project-create-form.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        Create Project
    </x-slot>


    <div class="container-fluid py-4 project-form-page">

        <div class="row justify-content-center">

            <div class="col-xl-8 col-lg-9">

                {{-- Header --}}
                <div class="project-form-header">

                    <div class="project-form-breadcrumb">

                        <a href="{{ route('projects.index') }}">
                            <i class="bi bi-arrow-left"></i>
                            Projects
                        </a>

                        <i class="bi bi-chevron-right"></i>

                        <span>
                            Create Project
                        </span>

                    </div>


                    <div class="project-form-title-wrapper">

                        <div class="project-form-title-icon">
                            <i class="bi bi-folder-plus"></i>
                        </div>

                        <div>

                            <h2 class="project-form-title">
                                Create Project
                            </h2>

                            <p class="project-form-subtitle">
                                Create a new project and start managing your tasks.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Form Card --}}
                <div class="project-form-card">

                    <div class="project-form-card-header">

                        <div>

                            <h5>
                                <i class="bi bi-folder2-open me-2"></i>
                                Project Details
                            </h5>

                            <p>
                                Enter the basic information for your project.
                            </p>

                        </div>

                    </div>


                    <div class="project-form-card-body">

                        {{-- Validation Errors --}}
                        @if($errors->any())

                            <div class="project-form-alert">

                                <div class="project-form-alert-title">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Please fix the following errors:
                                </div>

                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>

                            </div>

                        @endif


                        <form
                            action="{{ route('projects.store') }}"
                            method="POST"
                        >

                            @csrf


                            {{-- Project Name --}}
                            <div class="project-form-group">

                                <label
                                    for="name"
                                    class="project-form-label"
                                >
                                    Project Name
                                    <span>*</span>
                                </label>

                                <div class="project-input-wrapper">

                                    <i class="bi bi-folder"></i>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="project-form-input @error('name') is-invalid @enderror"
                                        placeholder="Enter project name"
                                        maxlength="150"
                                        required
                                        autofocus
                                    >

                                </div>

                                @error('name')
                                    <div class="project-form-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="project-form-group">

                                <label
                                    for="description"
                                    class="project-form-label"
                                >
                                    Description
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    class="project-form-textarea @error('description') is-invalid @enderror"
                                    placeholder="Describe the project..."
                                    maxlength="5000"
                                >{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="project-form-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="project-form-help">
                                    Maximum 5000 characters.
                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="project-form-group">

                                <label
                                    for="status"
                                    class="project-form-label"
                                >
                                    Status
                                    <span>*</span>
                                </label>

                                <div class="project-input-wrapper">

                                    <i class="bi bi-activity"></i>

                                    <select
                                        id="status"
                                        name="status"
                                        class="project-form-select @error('status') is-invalid @enderror"
                                        required
                                    >

                                        <option
                                            value="active"
                                            @selected(old('status', 'active') === 'active')
                                        >
                                            Active
                                        </option>

                                        <option
                                            value="completed"
                                            @selected(old('status') === 'completed')
                                        >
                                            Completed
                                        </option>

                                        <option
                                            value="archived"
                                            @selected(old('status') === 'archived')
                                        >
                                            Archived
                                        </option>

                                    </select>

                                </div>

                                @error('status')
                                    <div class="project-form-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Project Members --}}
                            <div
                                class="project-form-group"
                                x-data="{
                                    open: false,
                                    search: '',
                                    selected: @js(
                                        collect(old('members', []))
                                            ->map(fn ($id) => (string) $id)
                                            ->values()
                                            ->all()
                                    ),
                                    users: @js(
                                        $users->map(function ($user) {
                                            return [
                                                'id' => (string) $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                            ];
                                        })->values()->all()
                                    ),

                                    get filteredUsers() {
                                        const term = this.search.toLowerCase().trim();

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
                                        id = String(id);

                                        this.selected = this.selected.filter(
                                            selectedId => selectedId !== id
                                        );
                                    },

                                    get selectedUsers() {
                                        return this.users.filter(user =>
                                            this.selected.includes(String(user.id))
                                        );
                                    }
                                }"
                            >

                                <label class="project-form-label">
                                    Project Members
                                </label>


                                {{-- Hidden member inputs --}}
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
                                <div class="project-member-select-wrapper">

                                    <button
                                        type="button"
                                        class="project-member-select"
                                        @click="open = !open"
                                    >

                                        <span
                                            x-show="selected.length === 0"
                                            class="project-member-placeholder"
                                        >
                                            Select project members
                                        </span>

                                        <span
                                            x-show="selected.length > 0"
                                            class="project-member-selected-count"
                                        >
                                            <i class="bi bi-people-fill"></i>

                                            <span
                                                x-text="selected.length + ' member(s) selected'"
                                            ></span>
                                        </span>

                                        <i
                                            class="bi bi-chevron-down project-member-chevron"
                                            :class="{ 'rotate': open }"
                                        ></i>

                                    </button>


                                    {{-- Dropdown --}}
                                    <div
                                        x-show="open"
                                        x-cloak
                                        x-transition
                                        @click.outside="open = false"
                                        class="project-member-dropdown"
                                    >

                                        {{-- Search --}}
                                        <div class="project-member-search">

                                            <div class="project-member-search-wrapper">

                                                <i class="bi bi-search"></i>

                                                <input
                                                    type="text"
                                                    placeholder="Search users..."
                                                    x-model="search"
                                                    @click.stop
                                                >

                                            </div>

                                        </div>


                                        {{-- Users --}}
                                        <div class="project-member-list">

                                            <template
                                                x-for="user in filteredUsers"
                                                :key="user.id"
                                            >

                                                <button
                                                    type="button"
                                                    class="project-member-option"
                                                    :class="{ 'selected': isSelected(user.id) }"
                                                    @click="toggleUser(user.id)"
                                                >

                                                    {{-- Checkbox --}}
                                                    <div class="project-member-checkbox">

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
                                                        class="project-member-avatar"
                                                        x-text="user.name.charAt(0).toUpperCase()"
                                                    ></div>


                                                    {{-- User --}}
                                                    <div class="project-member-info">

                                                        <div
                                                            class="project-member-name"
                                                            x-text="user.name"
                                                        ></div>

                                                        <div
                                                            class="project-member-email"
                                                            x-text="user.email"
                                                        ></div>

                                                    </div>

                                                </button>

                                            </template>


                                            {{-- No users --}}
                                            <div
                                                x-show="filteredUsers.length === 0"
                                                class="project-member-empty"
                                            >

                                                <i class="bi bi-person-x"></i>

                                                <span>
                                                    No users found.
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Selected Members --}}
                                <div
                                    class="project-selected-members"
                                    x-show="selectedUsers.length > 0"
                                    x-cloak
                                >

                                    <template
                                        x-for="user in selectedUsers"
                                        :key="user.id"
                                    >

                                        <div class="project-selected-member">

                                            <span x-text="user.name"></span>

                                            <button
                                                type="button"
                                                @click="removeUser(user.id)"
                                                aria-label="Remove user"
                                            >
                                                <i class="bi bi-x"></i>
                                            </button>

                                        </div>

                                    </template>

                                </div>


                                @error('members')
                                    <div class="project-form-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @error('members.*')
                                    <div class="project-form-error">
                                        {{ $message }}
                                    </div>
                                @enderror


                                <div class="project-form-help">

                                    <i class="bi bi-info-circle me-1"></i>

                                    Select the users who should have access to this
                                    project. They can be assigned to tasks later.

                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="project-form-actions">

                                <a
                                    href="{{ route('projects.index') }}"
                                    class="btn btn-outline-secondary project-cancel-btn"
                                >
                                    <i class="bi bi-x-lg me-1"></i>
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary project-submit-btn"
                                >
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Create Project
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>