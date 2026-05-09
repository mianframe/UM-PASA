<footer class="mt-10 border-t border-white/10 pb-10 pt-8">
    <div class="page-wrap">
        <div class="glass-card px-6 py-8 sm:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                <div class="space-y-4">
                    <div>
                        <div class="text-2xl font-extrabold tracking-tight text-white">UM-Pasa</div>
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-[#eedcbbcc]">
                            Browse items, post listings, request transactions, open conversations, and track marketplace activity from one student workspace.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="glass-panel p-4">
                            <div class="text-sm font-semibold text-white">Help Area</div>
                            <p class="mt-2 text-sm text-[#eedcbbcc]">Read the quick instructions page for listing, requesting, messaging, and rating steps.</p>
                            <a href="{{ route('help') }}" class="mt-4 inline-flex text-sm font-semibold text-[#ff8c82]">Open Instructions</a>
                        </div>
                        <div class="glass-panel p-4">
                            <div class="text-sm font-semibold text-white">Contact Support</div>
                            <p class="mt-2 text-sm text-[#eedcbbcc]">Need help with your account or a marketplace action?</p>
                            <div class="mt-4 space-y-1 text-sm text-white">
                                <div>support@umpasa.local</div>
                                <div>Student Services Desk</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a href="{{ route('marketplace.index') }}" class="footer-pill">Browse Items</a>
                        <a href="{{ route('marketplace.index', ['type' => 'rent']) }}" class="footer-pill">Rental Items</a>
                        @auth
                            <a href="{{ route('messages.index') }}" class="footer-pill">Messages</a>
                            <a href="{{ route('dashboard') }}" class="footer-pill">Dashboard</a>
                        @endif
                        <a href="{{ route('help') }}" class="footer-pill">Help</a>
                    </div>

                    <div class="glass-panel p-4">
                        <div class="text-sm font-semibold text-white">Quick Instructions</div>
                        <ol class="mt-3 space-y-2 text-sm text-[#eedcbbcc]">
                            <li>1. Browse the marketplace or search by item type and category.</li>
                            <li>2. Open a listing and send a request or start a message.</li>
                            <li>3. Confirm the meetup details, complete the transaction, and leave feedback.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-white/10 pt-5 text-center text-sm text-[#eedcbb99]">
                UM-Pasa © {{ now()->year }}
            </div>
        </div>
    </div>
</footer>
