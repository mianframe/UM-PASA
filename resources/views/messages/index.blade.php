<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-kicker">Messages</p>
                <h1 class="section-title mt-2">Conversations and meetup planning</h1>
                <p class="section-copy mt-2">Use chat to ask about listings, coordinate details, and send meetup proposals without leaving the system.</p>
            </div>
            @if($activeConversation?->item)
                <a href="{{ route('marketplace.show', $activeConversation->item) }}" class="btn-secondary">View Linked Item</a>
            @endif
        </div>
    </x-slot>

    <div class="page-wrap space-y-6" x-data="{ meetupOpen: false }">
        <x-flash-messages />

        <div class="messages-shell">
            <aside class="messages-sidebar">
                <div class="messages-panel-header">
                    <div>
                        <p class="transaction-kicker">Inbox</p>
                        <h2>{{ $conversations->count() }} conversation{{ $conversations->count() === 1 ? '' : 's' }}</h2>
                    </div>
                </div>
                <div class="messages-list">
                    @forelse($conversations as $conversation)
                        @php
                            $other = $conversation->starter_id === auth()->id() ? $conversation->recipient : $conversation->starter;
                            $latest = $conversation->latestMessage;
                            $otherRatingCount = $other?->ratingsReceived()->count() ?? 0;
                        @endphp
                        <a href="{{ route('messages.show', $conversation) }}" class="message-thread-link {{ $activeConversation && $activeConversation->id === $conversation->id ? 'message-thread-active' : '' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="message-thread-name">{{ $other?->name }}</div>
                                    <div class="message-user-meta">
                                        <span>{{ str($other?->role ?? 'student')->title() }}</span>
                                        <span>{{ number_format($other?->average_rating ?? 0, 1) }} rating</span>
                                        <span>{{ $otherRatingCount }} review{{ $otherRatingCount === 1 ? '' : 's' }}</span>
                                    </div>
                                    <div class="message-thread-item">
                                        {{ $conversation->item?->title ? \Illuminate\Support\Str::limit($conversation->item->title, 38) : 'General conversation' }}
                                    </div>
                                    <div class="message-thread-preview">
                                        {{ $latest?->body ? \Illuminate\Support\Str::limit($latest->body, 44) : 'No messages yet' }}
                                    </div>
                                </div>
                                <div class="message-thread-time">
                                    {{ $conversation->last_message_at?->diffForHumans() ?? $conversation->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="messages-empty">
                            <div class="text-lg font-bold text-white">No conversations yet</div>
                            <p>Open any listing and click Message Seller to start your first chat.</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            <section class="messages-chat">
                @if($activeConversation)
                    @php
                        $other = $activeConversation->starter_id === auth()->id() ? $activeConversation->recipient : $activeConversation->starter;
                        $otherRatingCount = $other?->ratingsReceived()->count() ?? 0;
                    @endphp

                    <div class="messages-chat-header">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div class="min-w-0">
                                <p class="transaction-kicker">Conversation</p>
                                <h2>{{ $other?->name }}</h2>
                                <div class="message-header-meta">
                                    <span>{{ str($other?->role ?? 'student')->title() }}</span>
                                    <span>{{ number_format($other?->average_rating ?? 0, 1) }} rating</span>
                                    <span>{{ $otherRatingCount }} review{{ $otherRatingCount === 1 ? '' : 's' }}</span>
                                </div>
                                <p>{{ $activeConversation->item?->title ? 'Talking about: ' . $activeConversation->item->title : 'General marketplace conversation' }}</p>
                            </div>
                            <button type="button" class="btn-primary" @click="meetupOpen = true">Propose Meetup</button>
                        </div>
                    </div>

                    <div class="messages-stream">
                        @foreach($activeConversation->messages as $message)
                            @php $mine = $message->user_id === auth()->id(); @endphp
                            <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                                <div class="message-bubble {{ $mine ? 'message-bubble-mine' : 'message-bubble-theirs' }}">
                                    <div class="message-meta {{ $mine ? 'text-red-100' : 'text-slate-400' }}">
                                        <span>{{ $message->user->name }}</span>
                                        <span>{{ $message->created_at->format('M d, h:i A') }}</span>
                                    </div>

                                    @if($message->type === 'meetup_proposal')
                                        <div class="space-y-2">
                                            <div class="text-sm font-semibold">Meetup Proposal</div>
                                            <div class="text-sm">Location: {{ $message->meetup_location }}</div>
                                            <div class="text-sm">Time: {{ $message->meetup_time?->format('M d, Y h:i A') }}</div>
                                            @if($message->body)
                                                <div class="text-sm">{{ $message->body }}</div>
                                            @endif
                                            <div class="pt-2 text-xs uppercase tracking-[0.18em]">
                                                Status: {{ ucfirst($message->proposal_status ?? 'pending') }}
                                            </div>
                                            @if(!$mine && $message->proposal_status === 'pending')
                                                <div class="flex gap-2 pt-2">
                                                    <form method="POST" action="{{ route('messages.proposals.accept', $message) }}">
                                                        @csrf
                                                        <button type="submit" class="btn-primary !px-4 !py-2 text-xs">Accept</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('messages.proposals.decline', $message) }}">
                                                        @csrf
                                                        <button type="submit" class="btn-danger !px-4 !py-2 text-xs">Decline</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-sm leading-7">{{ $message->body }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="messages-compose">
                        <form method="POST" action="{{ route('messages.store') }}" class="space-y-3">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                            <textarea name="body" rows="3" class="glass-input mt-0" placeholder="Type your message here..."></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>

                    <div x-show="meetupOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/60" @click="meetupOpen = false"></div>
                    <div x-show="meetupOpen" x-transition class="fixed left-1/2 top-1/2 z-50 w-full max-w-lg -translate-x-1/2 -translate-y-1/2 px-4">
                        <div class="glass-card p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-black text-white">Send Meetup Proposal</h3>
                                <button type="button" class="btn-ghost" @click="meetupOpen = false">Close</button>
                            </div>
                            <form method="POST" action="{{ route('messages.store') }}" class="mt-5 space-y-4">
                                @csrf
                                <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                                <input type="hidden" name="type" value="meetup_proposal">
                                <div>
                                    <label class="text-sm font-medium text-[#eedcbb]">Location</label>
                                    <input type="text" name="meetup_location" class="glass-input" placeholder="Example: UM Library Lobby" required>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-[#eedcbb]">Date and Time</label>
                                    <input type="datetime-local" name="meetup_time" class="glass-input" required>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-[#eedcbb]">Note</label>
                                    <textarea name="body" rows="3" class="glass-input" placeholder="Can we meet here after class?"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="btn-primary">Send Proposal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="messages-empty messages-empty-large">
                        <div>
                            <div class="text-2xl font-black text-white">Your messages will appear here</div>
                            <p>Start from any listing or transaction to message another student and coordinate a meetup.</p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
