<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        {{ $title ?? 'TaskFlow' }}
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body>

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

                    {{-- Brand --}}
                    <div class="text-center mb-4">

                        <a
                            href="{{ url('/') }}"
                            class="text-decoration-none"
                        >

                            <div
                                class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white fw-bold mb-3"
                                style="width: 52px; height: 52px; font-size: 24px;"
                            >
                                T
                            </div>

                            <h3 class="fw-bold text-dark mb-1">
                                TaskFlow
                            </h3>

                        </a>

                        <p class="text-muted mb-0">
                            Manage your projects and tasks
                        </p>

                    </div>


                    {{-- Guest Content --}}
                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4 p-md-5">

                            {{ $slot }}

                        </div>

                    </div>


                    <div class="text-center mt-4">

                        <small class="text-muted">
                            &copy; {{ date('Y') }} TaskFlow
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>