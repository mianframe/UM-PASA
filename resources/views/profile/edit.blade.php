<x-app-layout>
    <x-slot name="header">
        <div class="profile-hero-header">
            <div>
                <p class="section-kicker">Account</p>
                <h2 class="section-title mt-2">{{ __('Profile') }}</h2>
                <p class="section-copy mt-2">Manage your account settings and preferences.</p>
            </div>
            <a href="{{ route('profile.ratings') }}" class="profile-ratings-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.6 5.3 5.9.9-4.3 4.2 1 5.9-5.2-2.8-5.2 2.8 1-5.9-4.3-4.2 5.9-.9L12 3Z"></path>
                </svg>
                View Ratings
            </a>
        </div>
    </x-slot>

    <div class="page-wrap">
        <div class="profile-shell">
            <div class="profile-grid">
                <div class="profile-panel">
                        @include('profile.partials.update-profile-information-form')
                </div>

                <div class="profile-panel">
                        @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-danger-panel">
                    @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
