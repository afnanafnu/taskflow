<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'TaskFlow' }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body>

<div
    x-data="{
        sidebarOpen: false,
        closeSidebarOnMobile() {
            if (window.innerWidth < 992) {
                this.sidebarOpen = false;
            }
        }
    }"
    class="taskflow-app"
>

    <div
        class="sidebar-overlay"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        x-cloak
    ></div>

    @include('layouts.sidebar')

    <div class="taskflow-main">

        <header class="taskflow-navbar">

            <div class="d-flex align-items-center">

                <button
                    type="button"
                    class="sidebar-toggle d-lg-none"
                    @click="sidebarOpen = true"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="navbar-page-title">
                    {{ $title ?? 'Dashboard' }}
                </div>

            </div>

            <div class="navbar-user">

                <div class="navbar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="d-none d-sm-block">
                    <div class="navbar-user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="navbar-user-role">
                        {{ ucfirst(auth()->user()->role) }}
                    </div>
                </div>

            </div>

        </header>

        <main class="taskflow-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

            {{ $slot }}

        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>