<x-taskflow-layout>

    <x-slot name="title">
        Profile
    </x-slot>

    <div class="container-fluid py-4">

        <div class="row justify-content-center">

            <div class="col-12 col-xl-10">

                {{-- Profile Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">

                            <h4 class="fw-bold mb-1">
                                Profile Information
                            </h4>

                            <p class="text-muted mb-0">
                                Update your account's profile information and email address.
                            </p>

                        </div>

                        @include(
                            'profile.partials.update-profile-information-form'
                        )

                    </div>

                </div>


                {{-- Update Password --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">

                            <h4 class="fw-bold mb-1">
                                Update Password
                            </h4>

                            <p class="text-muted mb-0">
                                Make sure your account is using a long, random password to stay secure.
                            </p>

                        </div>

                        @include(
                            'profile.partials.update-password-form'
                        )

                    </div>

                </div>


                {{-- Delete Account --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">

                            <h4 class="fw-bold mb-1 text-danger">
                                Delete Account
                            </h4>

                            <p class="text-muted mb-0">
                                Permanently delete your account and all of its associated data.
                            </p>

                        </div>

                        @include(
                            'profile.partials.delete-user-form'
                        )

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-taskflow-layout>