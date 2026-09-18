<aside class="taskflow-sidebar" :class="{ 'is-open': sidebarOpen }">
    {{-- Header --}}
    <div class="sidebar-header">

        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <span class="brand-icon">T</span>
            <span>TaskFlow</span>
        </a>

        <button type="button" class="sidebar-close d-lg-none" @click="sidebarOpen = false" aria-label="Close sidebar">
            &times;
        </button>

    </div>


    {{-- Logged-in User --}}
    <div class="sidebar-user">

        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div class="sidebar-user-info">

            <div class="sidebar-user-name">
                {{ auth()->user()->name }}
            </div>

            <div class="sidebar-user-role">
                {{ ucfirst(auth()->user()->role) }}
            </div>

        </div>

    </div>


    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- ========================= --}}
        {{-- OVERVIEW --}}
        {{-- ========================= --}}

        <div class="sidebar-section-title">
            Overview
        </div>


        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            @click="closeSidebarOnMobile()">
            <span class="sidebar-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>

            <span>Dashboard</span>
        </a>


        {{-- ========================= --}}
        {{-- WORKSPACE --}}
        {{-- ========================= --}}

        <div class="sidebar-section-title mt-3">
            Workspace
        </div>


        {{-- Projects --}}
        @can('viewAny', App\Models\Project::class)
            <a href="{{ route('projects.index') }}"
                class="sidebar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-folder-fill"></i>
                </span>

                <span>Projects</span>
            </a>
        @endcan


        {{-- Create Project --}}
        @can('create', App\Models\Project::class)
            <a href="{{ route('projects.create') }}"
                class="sidebar-link {{ request()->routeIs('projects.create') ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-folder-plus"></i>
                </span>

                <span>Create Project</span>
            </a>
        @endcan


        {{-- ========================= --}}
        {{-- TASKS --}}
        {{-- ========================= --}}

        @can('viewAny', App\Models\Task::class)
            <div class="sidebar-section-title mt-3">
                Tasks
            </div>

            {{-- All Tasks --}}
            <a href="{{ route('tasks.index') }}"
                class="sidebar-link {{ request()->routeIs('tasks.index') && !request()->query('status') && !request()->query('filter') ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-list-check"></i>
                </span>

                <span>All Tasks</span>
            </a>


            {{-- Current Tasks --}}
            <a href="{{ route('tasks.index', ['filter' => 'current']) }}"
                class="sidebar-link {{ request()->query('filter') === 'current' ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-hourglass-split"></i>
                </span>

                <span>Current Tasks</span>
            </a>


            {{-- Completed --}}
            <a href="{{ route('tasks.index', ['status' => 'completed']) }}"
                class="sidebar-link {{ request()->query('status') === 'completed' ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-check-circle"></i>
                </span>

                <span>Completed</span>
            </a>


            {{-- In Progress --}}
            <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}"
                class="sidebar-link {{ request()->query('status') === 'in_progress' ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </span>

                <span>In Progress</span>
            </a>


            {{-- Blocked --}}
            <a href="{{ route('tasks.index', ['status' => 'blocked']) }}"
                class="sidebar-link {{ request()->query('status') === 'blocked' ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-exclamation-octagon"></i>
                </span>

                <span>Blocked</span>
            </a>
        @endcan


        {{-- ========================= --}}
        {{-- ADMINISTRATION --}}
        {{-- ========================= --}}

        @if (auth()->user()->isAdmin())
            <div class="sidebar-section-title mt-3">
                Administration
            </div>


            {{-- Users --}}
            <a href="{{ route('users.index') }}"
                class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-people-fill"></i>
                </span>

                <span>Users</span>
            </a>


            {{-- Add User --}}
            <a href="{{ route('users.create') }}"
                class="sidebar-link {{ request()->routeIs('users.create') ? 'active' : '' }}"
                @click="closeSidebarOnMobile()">
                <span class="sidebar-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </span>

                <span>Add User</span>
            </a>


            {{-- Admin information --}}
            <div class="sidebar-link sidebar-info-link">

                <span class="sidebar-icon">
                    <i class="bi bi-shield-check"></i>
                </span>

                <span>Administrator Access</span>

            </div>
        @endif


        {{-- ========================= --}}
        {{-- ACCOUNT --}}
        {{-- ========================= --}}

        <div class="sidebar-section-title mt-3">
            Account
        </div>


        {{-- Profile --}}
        <a href="{{ route('profile.edit') }}"
            class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" @click="closeSidebarOnMobile()">
            <span class="sidebar-icon">
                <i class="bi bi-person-circle"></i>
            </span>

            <span>Profile</span>
        </a>

    </nav>


    {{-- ========================= --}}
    {{-- FOOTER --}}
    {{-- ========================= --}}

    <div class="sidebar-footer">

        <div class="sidebar-role-card">

            <div class="sidebar-role-icon">
                <i class="bi bi-person-badge"></i>
            </div>

            <div>

                <div class="sidebar-role-title">
                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Member' }}
                </div>

                <div class="sidebar-role-text">
                    {{ auth()->user()->isAdmin() ? 'Full system access' : 'Project access' }}
                </div>

            </div>

        </div>


        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf

            <button type="submit" class="sidebar-link sidebar-logout w-100 border-0">
                <span class="sidebar-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </span>

                <span>Logout</span>
            </button>

        </form>

    </div>

</aside>
