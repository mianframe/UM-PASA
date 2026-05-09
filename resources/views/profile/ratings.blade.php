<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Profile Ratings') }}</h2>
                <p class="text-sm text-gray-500 mt-1">See ratings left by other UM students.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md">Edit Profile</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Average Rating</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $user->average_rating }}</div>
                    </div>
                    <div class="text-sm text-gray-500">Total reviews: {{ $ratings->count() }}</div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($ratings as $rating)
                    <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm text-gray-500">From {{ $rating->reviewer->name }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $rating->rating }} of 5 stars</p>
                            </div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">{{ $rating->created_at->format('M d, Y') }}</div>
                        </div>
                        @if($rating->comment)
                            <p class="mt-4 text-gray-600">{{ $rating->comment }}</p>
                        @else
                            <p class="mt-4 text-gray-500">No comment provided.</p>
                        @endif
                    </div>
                @empty
                    <div class="rounded-3xl bg-gray-50 border border-gray-200 p-8 text-center text-gray-600">No ratings yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
