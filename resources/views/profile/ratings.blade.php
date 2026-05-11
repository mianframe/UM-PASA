<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Profile</p>
                <h1 class="section-title mt-2">Ratings and Feedback</h1>
                <p class="section-copy mt-2">See ratings left by other UM students after completed transactions.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn-secondary">Edit Profile</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="glass-card p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-sm uppercase tracking-[0.18em] text-slate-400">Average Rating</div>
                    <div class="mt-2 text-4xl font-black text-white">{{ number_format($user->average_rating, 1) }}</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-sm font-semibold text-slate-200">
                    {{ $ratings->count() }} review{{ $ratings->count() === 1 ? '' : 's' }}
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($ratings as $rating)
                <div class="rating-review-card p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="transaction-kicker">From {{ $rating->reviewer->name }}</p>
                            <h3>{{ $rating->rating }} of 5 stars</h3>
                        </div>
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-400">{{ $rating->created_at->format('M d, Y') }}</div>
                    </div>
                    <p class="mt-4 text-sm leading-7 text-slate-300">{{ $rating->comment ?: 'No comment provided.' }}</p>
                </div>
            @empty
                <div class="glass-card px-6 py-12 text-center">
                    <h3 class="text-lg font-semibold text-white">No ratings yet</h3>
                    <p class="mt-2 text-sm text-slate-300">Feedback will appear here after completed transactions are rated.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
