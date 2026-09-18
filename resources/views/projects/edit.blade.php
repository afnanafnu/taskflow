@php
    $selectedMembers = old(
        'members',
        $project->users
            ->where('id', '!=', $project->owner_id)
            ->pluck('id')
            ->map(function ($id) {
                return (string) $id;
            })
            ->values()
            ->all()
    );

    $memberOptions = $users
        ->map(function ($user) {
            return [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        })
        ->values()
        ->all();
@endphp

@push('styles')
    @vite('resources/css/projects/project-edit-form.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        Edit Project
    </x-slot>

    <div class="container-fluid py-4 project-form-page">

        {{-- Page Header --}}
        <div class="project-form-header">

            <div class="project-form-breadcrumb">
                <a href="{{ route('projects.index') }}">
                    <i class="bi bi-folder-fill"></i>
                    Projects
                </a>

                <i class="bi bi-chevron-right"></i>

                <a href="{{ route('projects.show', $project) }}">
                    {{ $project->name }}
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>Edit</span>
            </div>

            <div class="project-form-title-wrapper">

                <div class="project-form-title-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h2 class="project-form-title">
                        Edit Project
                    </h2>

                    <p class="project-form-subtitle">
                        Update project details and manage project members.
                    </p>
                </div>

            </div>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="project-form-error-summary">

                <div class="project-form-error-title">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Please fix the following errors
                </div>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <div class="row g-4">

            {{-- Main Form --}}
            <div class="col-xl-8 col-lg-8">

                <div class="project-form-card">

                    <div class="project-form-card-header">

                        <div>
                            <h5>
                                <i class="bi bi-sliders me-2"></i>
                                Project Details
                            </h5>

                            <p>
                                Update the information for this project.
                            </p>
                        </div>

                        <div class="project-form-id">
                            #{{ $project->id }}
                        </div>

                    </div>


                    <div class="project-form-card-body">

                        <form
                            action="{{ route('projects.update', $project) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PUT')


                            {{-- Project Name --}}
                            <div class="project-form-group">

                                <label
                                    for="name"
                                    class="project-form-label"
                                >
                                    Project Name
                                    <span>*</span>
                                </label>

                                <div class="project-form-input-wrapper">

                                    <i class="bi bi-folder2-open"></i>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $project->name) }}"
                                        class="project-form-input @error('name') is-invalid @enderror"
                                        placeholder="Enter project name"
                                        maxlength="150"
                                        required
                                        autofocus
                                    >

                                </div>

                                @error('name')
                                    <div class="project-form-field-error">
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
                                    rows="6"
                                    class="project-form-textarea @error('description') is-invalid @enderror"
                                    placeholder="Describe the project..."
                                    maxlength="5000"
                                >{{ old('description', $project->description) }}</textarea>

                                @error('description')
                                    <div class="project-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="project-form-help">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Maximum 5000 characters.
                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="project-form-group">

                                <label
                                    for="status"
                                    class="project-form-label"
                                >
                                    Project Status
                                    <span>*</span>
                                </label>

                                <div class="project-form-input-wrapper">

                                    <i class="bi bi-activity"></i>

                                    <select
                                        id="status"
                                        name="status"
                                        class="project-form-select @error('status') is-invalid @enderror"
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

                                </div>

                                @error('status')
                                    <div class="project-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Members --}}
                            <div
                                class="project-form-group"
                                x-data="{
                                    open: false,
                                    search: '',
                                    selected: @js($selectedMembers),
                                    users: @js($memberOptions),

                                    get filteredUsers() {
                                        const term = this.search.toLowerCase().trim();

                                        if (!term) {
                                            return this.users;
                                        }

                                        return this.users.filter(user => {
                                            return user.name.toLowerCase().includes(term)
                                                || user.email.toLowerCase().includes(term);
                                        });
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
                                        return this.users.filter(user => {
                                            return this.selected.includes(String(user.id));
                                        });
                                    }
                                }"
                            >

                                <label class="project-form-label">
                                    Project Members
                                </label>

                                {{-- Hidden Inputs --}}
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
                                <div class="project-member-selector">

                                    <button
                                        type="button"
                                        class="project-member-trigger"
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
                                            x-text="selected.length + ' member(s) selected'"
                                        ></span>

                                        <i
                                            class="bi bi-chevron-down"
                                            :class="{ 'rotate': open }"
                                        ></i>

                                    </button>


                                    {{-- Dropdown --}}
                                    <div
                                        x-show="open"
                                        x-transition
                                        @click.outside="open = false"
                                        x-cloak
                                        class="project-member-dropdown"
                                    >

                                        {{-- Search --}}
                                        <div class="project-member-search">

                                            <div class="project-member-search-box">

                                                <i class="bi bi-search"></i>

                                                <input
                                                    type="text"
                                                    placeholder="Search users..."
                                                    x-model="search"
                                                    @click.stop
                                                >

                                                <button
                                                    type="button"
                                                    x-show="search"
                                                    @click="search = ''"
                                                    x-cloak
                                                >
                                                    <i class="bi bi-x"></i>
                                                </button>

                                            </div>

                                        </div>


                                        {{-- User List --}}
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

                                                    <div class="project-member-checkbox">

                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            :checked="isSelected(user.id)"
                                                            @click.stop
                                                            @change="toggleUser(user.id)"
                                                        >

                                                    </div>


                                                    <div
                                                        class="project-member-avatar"
                                                        x-text="user.name.charAt(0).toUpperCase()"
                                                    ></div>


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

                                                    <i
                                                        class="bi bi-check-lg project-member-check"
                                                        x-show="isSelected(user.id)"
                                                    ></i>

                                                </button>

                                            </template>


                                            {{-- No Users --}}
                                            <div
                                                x-show="filteredUsers.length === 0"
                                                x-cloak
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

                                    <div class="project-selected-title">
                                        Selected members
                                    </div>

                                    <div class="project-selected-list">

                                        <template
                                            x-for="user in selectedUsers"
                                            :key="user.id"
                                        >

                                            <span class="project-selected-member">

                                                <span
                                                    class="project-selected-avatar"
                                                    x-text="user.name.charAt(0).toUpperCase()"
                                                ></span>

                                                <span
                                                    class="project-selected-name"
                                                    x-text="user.name"
                                                ></span>

                                                <button
                                                    type="button"
                                                    @click="removeUser(user.id)"
                                                    aria-label="Remove member"
                                                >
                                                    <i class="bi bi-x"></i>
                                                </button>

                                            </span>

                                        </template>

                                    </div>

                                </div>


                                @error('members')
                                    <div class="project-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @error('members.*')
                                    <div class="project-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="project-form-help">
                                    <i class="bi bi-shield-check me-1"></i>
                                    The project owner is always retained as manager.
                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="project-form-actions">

                                <a
                                    href="{{ route('projects.index', $project) }}"
                                    class="project-form-cancel"
                                >
                                    <i class="bi bi-x-lg"></i>
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="project-form-submit"
                                >
                                    <i class="bi bi-check-lg"></i>
                                    Update Project
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- Sidebar --}}
            <div class="col-xl-4 col-lg-4">

                <div class="project-form-side-card">

                    <div class="project-form-side-icon">
                        <i class="bi bi-folder-fill"></i>
                    </div>

                    <h5>
                        {{ $project->name }}
                    </h5>

                    <p>
                        Keep your project information up to date and make sure the right team members have access.
                    </p>

                    <div class="project-form-side-divider"></div>

                    <div class="project-form-meta">

                        <div class="project-form-meta-item">

                            <span>
                                <i class="bi bi-person-fill"></i>
                                Owner
                            </span>

                            <strong>
                                {{ $project->owner->name ?? 'N/A' }}
                            </strong>

                        </div>

                        <div class="project-form-meta-item">

                            <span>
                                <i class="bi bi-people-fill"></i>
                                Members
                            </span>

                            <strong>
                                {{ $project->users->count() }}
                            </strong>

                        </div>

                        <div class="project-form-meta-item">

                            <span>
                                <i class="bi bi-check2-square"></i>
                                Tasks
                            </span>

                            <strong>
                                {{ $project->tasks->count() }}
                            </strong>

                        </div>

                        <div class="project-form-meta-item">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Created
                            </span>

                            <strong>
                                {{ $project->created_at->format('d M Y') }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Update Tips --}}
                <div class="project-form-tips-card">

                    <div class="project-form-tips-title">
                        <i class="bi bi-lightbulb-fill"></i>
                        Update Tips
                    </div>

                    <ul>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Keep the project name clear and descriptive.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Update the status when the project progresses.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Add only users who need project access.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Removing a member will unassign their project tasks.
                        </li>

                    </ul>

                </div>


                {{-- Back Button --}}
                <a
                    href="{{ route('projects.index', $project) }}"
                    class="project-form-view-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Project
                </a>

            </div>

        </div>

    </div>

</x-taskflow-layout>