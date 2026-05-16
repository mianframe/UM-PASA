<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Notifications</p>
                <h1 class="section-title mt-2">Activity Updates</h1>
                <p class="section-copy mt-2">Review requests, approvals, pending items, rejections, messages, and ratings.</p>
            </div>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn-secondary">Mark All Read</button>
            </form>
        </div>
    </x-slot>

    @php
        $filters = [
            'all' => 'All',
            'unread' => 'Unread',
            'requests' => 'Requests',
            'approved' => 'Approved',
            'pending' => 'Pending',
            'rejected' => 'Rejected',
            'ratings' => 'Ratings',
        ];
    @endphp

    <div class="page-wrap space-y-6">
        <div class="flex flex-wrap gap-2">
            @foreach($filters as $filterKey => $filterLabel)
                <a href="{{ route('notifications.index', ['filter' => $filterKey]) }}" class="notification-filter-chip {{ $filter === $filterKey ? 'notification-filter-chip-active' : '' }}">
                    {{ $filterLabel }}
                </a>
            @endforeach
        </div>

        <div class="space-y-8">
            @if($notifications->isNotEmpty())
            @foreach($groupedNotifications as $groupLabel => $group)
                @continue($group->isEmpty())
                <section class="space-y-3">
                    <div class="flex items-center gap-3">
                        <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-[#f3df32]">{{ $groupLabel }}</h2>
                        <span class="h-px flex-1 bg-white/10"></span>
                    </div>

                    @foreach($group as $notification)
                        @php
                            $tone = match($notification->type) {
                                'rejection', 'rental_overdue' => 'rejected',
                                'rental_due_soon', 'rental_due', 'payment_proof' => 'pending',
                                'approval', 'completion' => 'approved',
                                'request', 'message', 'meetup' => 'request',
                                'rating' => 'rating',
                                default => 'default',
                            };

                            $targetUrl = match(true) {
                                $notification->related instanceof \App\Models\Transaction => route('transactions.show', $notification->related),
                                $notification->related instanceof \App\Models\Conversation => route('messages.show', $notification->related),
                                $notification->related instanceof \App\Models\Item => route('marketplace.show', $notification->related),
                                default => match($notification->type) {
                                    'request', 'approval', 'rejection', 'completion', 'rating', 'payment_proof', 'rental_due_soon', 'rental_due', 'rental_overdue' => $notification->related_id ? route('transactions.show', $notification->related_id) : null,
                                    'message', 'meetup' => $notification->related_id ? route('messages.show', $notification->related_id) : null,
                                    default => null,
                                },
                            };
                        @endphp

                        <article class="notification-card notification-{{ $tone }} {{ $notification->is_read ? '' : 'notification-unread' }}">
                            <div class="notification-status-icon">
                                @if(! $notification->is_read)
                                    <span class="notification-unread-dot"></span>
                                @endif
                                <span>{{ str($notification->type)->replace('_', ' ')->title() }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold leading-6 text-white">{{ $notification->message }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if($targetUrl)
                                <a href="{{ $targetUrl }}" class="notification-action">View Details</a>
                            @else
                                <span class="notification-action notification-action-disabled">View Details</span>
                            @endif
                        </article>
                    @endforeach
                </section>
            @endforeach
            @else
                <div class="glass-card px-6 py-12 text-center">
                    <h3 class="text-lg font-semibold text-white">No notifications available</h3>
                    <p class="mt-2 text-sm text-slate-300">Once students start requesting items, updates will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
