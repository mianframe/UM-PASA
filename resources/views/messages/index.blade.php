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

        <div class="grid gap-6 xl:grid-cols-[0.36fr_0.64fr]">
            <aside class="glass-card overflow-hidden">
                <div class="border-b border-white/10 px-5 py-4">
                    <h2 class="text-lg font-bold text-white">Inbox</h2>
                    <p class="mt-1 text-sm text-[#eedcbbcc]">{{ $conversations->count() }} conversation{{ $conversations->count() === 1 ? '' : 's' }}</p>
                </div>
                <div class="max-h-[42rem] overflow-y-auto">
                    @forelse($conversations as $conversation)
                        @php
                            $other = $conversation->starter_id === auth()->id() ? $conversation->recipient : $conversation->starter;
                            $latest = $conversation->latestMessage;
                        @endphp
                        <a href="{{ route('messages.show', $conversation) }}" class="block border-b border-white/5 px-5 py-4 transition hover:bg-white/5 {{ $activeConversation && $activeConversation->id === $conversation->id ? 'bg-white/5' : '' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-white">{{ $other?->name }}</div>
                                    <div class="mt-1 text-sm text-[#eedcbbcc]">
                                        {{ $conversation->item?->title ? \Illuminate\Support\Str::limit($conversation->item->title, 38) : 'General conversation' }}
                                    </div>
                                    <div class="mt-2 text-xs text-slate-400">
                                        {{ $latest?->body ? \Illuminate\Support\Str::limit($latest->body, 44) : 'No messages yet' }}
                                    </div>
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $conversation->last_message_at?->diffForHumans() ?? $conversation->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <div class="text-lg font-bold text-white">No conversations yet</div>
                            <p class="mt-2 text-sm text-[#eedcbbcc]">Open any listing and click <strong>Message Seller</strong> to start your first chat.</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            <section class="glass-card overflow-hidden">
                @if($activeConversation)
                    @php
                        $other = $activeConversation->starter_id === auth()->id() ? $activeConversation->recipient : $activeConversation->starter;
                    @endphp

                    <div class="border-b border-white/10 px-6 py-5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <h2 class="text-2xl font-black text-white">{{ $other?->name }}</h2>
                                <p class="mt-2 text-sm text-[#eedcbbcc]">
                                    {{ $activeConversation->item?->title ? 'Talking about: ' . $activeConversation->item->title : 'General marketplace conversation' }}
                                </p>
                            </div>
                            <button type="button" class="btn-primary" @click="meetupOpen = true">Propose Meetup</button>
                        </div>
                    </div>

                    <div class="max-h-[32rem] space-y-4 overflow-y-auto px-6 py-6">
                        @foreach($activeConversation->messages as $message)
                            @php $mine = $message->user_id === auth()->id(); @endphp
                            <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[85%] rounded-[1.5rem] px-4 py-3 {{ $mine ? 'bg-red-700 text-white' : 'bg-white/6 text-[#eedcbb]' }}">
                                    <div class="mb-2 flex items-center gap-3 text-xs uppercase tracking-[0.18em] {{ $mine ? 'text-red-100' : 'text-slate-400' }}">
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

                    <div class="border-t border-white/10 px-6 py-5">
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
                    <div class="flex min-h-[36rem] items-center justify-center px-6 py-16 text-center">
                        <div>
                            <div class="text-2xl font-black text-white">Your messages will appear here</div>
                            <p class="mt-3 text-sm text-[#eedcbbcc]">Start from any listing to message a seller and coordinate a meetup.</p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
