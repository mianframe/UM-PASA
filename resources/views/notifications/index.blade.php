<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Notifications</p>
                <h1 class="section-title mt-2">Activity Updates</h1>
                <p class="section-copy mt-2">Review requests, approvals, payment proof, rental due dates, completions, and ratings.</p>
            </div>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn-secondary">Mark All Read</button>
            </form>
        </div>
    </x-slot>

    <div class="page-wrap">
        <div class="space-y-4">
            @forelse($notifications as $notification)
                @php
                    $typeClasses = match($notification->type) {
                        'request' => 'bg-amber-500/15 text-amber-100 border-amber-400/20',
                        'approval' => 'bg-sky-500/15 text-sky-100 border-sky-400/20',
                        'rejection' => 'bg-red-500/15 text-red-100 border-red-400/20',
                        'completion' => 'bg-emerald-500/15 text-emerald-100 border-emerald-400/20',
                        'rating' => 'bg-fuchsia-500/15 text-fuchsia-100 border-fuchsia-400/20',
                        'payment_proof' => 'bg-violet-500/15 text-violet-100 border-violet-400/20',
                        'rental_due_soon', 'rental_due' => 'bg-amber-500/15 text-amber-100 border-amber-400/20',
                        'rental_overdue' => 'bg-red-500/15 text-red-100 border-red-400/20',
                        'message' => 'bg-white/10 text-white border-white/10',
                        'meetup' => 'bg-[#f3df3215] text-[#fff2a0] border-[#f3df3230]',
                        default => 'bg-white/10 text-white border-white/10',
                    };

                    $targetUrl = match($notification->type) {
                        'request', 'approval', 'rejection', 'completion', 'rating', 'payment_proof', 'rental_due_soon', 'rental_due', 'rental_overdue' => $notification->related_id ? route('transactions.show', $notification->related_id) : null,
                        'message', 'meetup' => $notification->related_id ? route('messages.show', $notification->related_id) : null,
                        default => null,
                    };
                @endphp

                <div class="glass-card p-5 {{ $notification->is_read ? '' : 'border-red-400/20 bg-red-600/10' }}">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-sm text-white">{{ $notification->message }}</div>
                            <div class="mt-3 flex items-center gap-3">
                                <span class="badge-base {{ $typeClasses }}">{{ str($notification->type)->replace('_', ' ')->title() }}</span>
                                @if($targetUrl)
                                    <a href="{{ $targetUrl }}" class="text-xs font-semibold uppercase tracking-[0.18em] text-[#f3df32]">Open</a>
                                @endif
                            </div>
                        </div>
                        <button type="button" class="btn-secondary pointer-events-none">{{ $notification->is_read ? 'Read' : 'New' }}</button>
                    </div>
                    <div class="mt-3 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <div class="glass-card px-6 py-12 text-center">
                    <h3 class="text-lg font-semibold text-white">No notifications available</h3>
                    <p class="mt-2 text-sm text-slate-300">Once students start requesting items, updates will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
