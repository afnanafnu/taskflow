@push('styles')
    @vite('resources/css/users/users-form.css')
@endpush

<x-taskflow-layout>

    <x-slot name="title">
        Edit User
    </x-slot>

    <div class="container-fluid py-4 user-form-page">

        <div class="user-form-header">

            <div class="user-form-breadcrumb">

                <a href="{{ route('users.index') }}">
                    <i class="bi bi-people-fill"></i>
                    Users
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>{{ $user->name }}</span>

                <i class="bi bi-chevron-right"></i>

                <span>Edit</span>

            </div>

            <div class="user-form-title-wrapper">

                <div class="user-form-title-icon">
                    <i class="bi bi-person-gear"></i>
                </div>

                <div>
                    <h2 class="user-form-title">
                        Edit User
                    </h2>

                    <p class="user-form-subtitle">
                        Update user information and access role.
                    </p>
                </div>

            </div>

        </div>


        @if($errors->any())

            <div class="user-form-error-summary">

                <div class="user-form-error-title">
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

            <div class="col-xl-8 col-lg-8">

                <div class="user-form-card">

                    <div class="user-form-card-header">

                        <div>
                            <h5>
                                <i class="bi bi-person-vcard me-2"></i>
                                User Details
                            </h5>

                            <p>
                                Update this user's account information.
                            </p>
                        </div>

                        <div class="user-form-id">
                            #{{ $user->id }}
                        </div>

                    </div>


                    <div class="user-form-card-body">

                        <form
                            action="{{ route('users.update', $user) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PUT')


                            {{-- Name --}}
                            <div class="user-form-group">

                                <label
                                    for="name"
                                    class="user-form-label"
                                >
                                    Full Name
                                    <span>*</span>
                                </label>

                                <div class="user-form-input-wrapper">

                                    <i class="bi bi-person"></i>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        class="user-form-input @error('name') is-invalid @enderror"
                                        maxlength="100"
                                        required
                                        autofocus
                                    >

                                </div>

                                @error('name')
                                    <div class="user-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Email --}}
                            <div class="user-form-group">

                                <label
                                    for="email"
                                    class="user-form-label"
                                >
                                    Email Address
                                    <span>*</span>
                                </label>

                                <div class="user-form-input-wrapper">

                                    <i class="bi bi-envelope"></i>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        class="user-form-input @error('email') is-invalid @enderror"
                                        required
                                    >

                                </div>

                                @error('email')
                                    <div class="user-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Role --}}
                            <div class="user-form-group">

                                <label
                                    for="role"
                                    class="user-form-label"
                                >
                                    Role
                                    <span>*</span>
                                </label>

                                <div class="user-form-input-wrapper">

                                    <i class="bi bi-shield"></i>

                                    <select
                                        id="role"
                                        name="role"
                                        class="user-form-select @error('role') is-invalid @enderror"
                                        required
                                    >

                                        <option
                                            value="user"
                                            @selected(old('role', $user->role) === 'user')
                                        >
                                            User
                                        </option>

                                        <option
                                            value="admin"
                                            @selected(old('role', $user->role) === 'admin')
                                        >
                                            Administrator
                                        </option>

                                    </select>

                                </div>

                                @error('role')
                                    <div class="user-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Password --}}
                            <div class="user-form-group">

                                <label
                                    for="password"
                                    class="user-form-label"
                                >
                                    New Password
                                </label>

                                <div class="user-form-input-wrapper">

                                    <i class="bi bi-lock"></i>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="user-form-input @error('password') is-invalid @enderror"
                                        placeholder="Leave blank to keep current password"
                                    >

                                </div>

                                @error('password')
                                    <div class="user-form-field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="user-form-help">
                                    Leave this field empty if you don't want to change the password.
                                </div>

                            </div>


                            {{-- Confirm Password --}}
                            <div class="user-form-group">

                                <label
                                    for="password_confirmation"
                                    class="user-form-label"
                                >
                                    Confirm New Password
                                </label>

                                <div class="user-form-input-wrapper">

                                    <i class="bi bi-lock-fill"></i>

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="user-form-input"
                                        placeholder="Confirm new password"
                                    >

                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="user-form-actions">

                                <a
                                    href="{{ route('users.index') }}"
                                    class="user-form-cancel"
                                >
                                    <i class="bi bi-x-lg"></i>
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="user-form-submit"
                                >
                                    <i class="bi bi-check-lg"></i>
                                    Update User
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- User Information --}}
            <div class="col-xl-4 col-lg-4">

                <div class="user-form-side-card">

                    <div class="user-form-side-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>

                    <h5>
                        {{ $user->name }}
                    </h5>

                    <p>
                        {{ $user->email }}
                    </p>

                    <div class="user-form-side-divider"></div>

                    <div class="user-form-meta">

                        <div class="user-form-meta-item">

                            <span>
                                <i class="bi bi-shield"></i>
                                Role
                            </span>

                            <strong>
                                {{ ucfirst($user->role) }}
                            </strong>

                        </div>

                        <div class="user-form-meta-item">

                            <span>
                                <i class="bi bi-folder"></i>
                                Projects
                            </span>

                            <strong>
                                {{ $user->owned_projects_count ?? 0 }}
                            </strong>

                        </div>

                        <div class="user-form-meta-item">

                            <span>
                                <i class="bi bi-check2-square"></i>
                                Tasks
                            </span>

                            <strong>
                                {{ $user->assigned_tasks_count ?? 0 }}
                            </strong>

                        </div>

                        <div class="user-form-meta-item">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Joined
                            </span>

                            <strong>
                                {{ $user->created_at->format('d M Y') }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>