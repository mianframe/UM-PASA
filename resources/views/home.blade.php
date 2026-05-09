<x-app-layout>
    <div class="page-wrap space-y-10">
        <section class="hero-shell">
            <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div class="space-y-6">
                    <p class="section-kicker">University Marketplace</p>
                    <div class="space-y-4">
                        <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">
                            Buy, sell, rent, swap, and message for campus essentials in one place.
                        </h1>
                        <p class="max-w-2xl text-base leading-8 text-[#eedcbbd8] sm:text-lg">
                            Find academic items, manage listings, open conversations, and track transactions from one marketplace built for UM students.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('marketplace.index') }}" class="btn-primary">Browse Marketplace</a>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn-secondary">Post a Listing</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-secondary">Create Student Account</a>
                        @endauth
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="glass-card p-6 sm:col-span-2">
                        <div class="text-sm font-semibold uppercase tracking-[0.24em] text-[#f3df32]">Core Flow</div>
                        <div class="mt-3 text-2xl font-black text-white">List item → receive message → propose meetup → complete transaction</div>
                    </div>
                    <div class="glass-card p-6">
                        <div class="text-sm text-[#eedcbbcc]">Account Access</div>
                        <div class="mt-2 text-3xl font-black text-white">UM Emails Only</div>
                    </div>
                    <div class="glass-card p-6">
                        <div class="text-sm text-[#eedcbbcc]">Support</div>
                        <div class="mt-2 text-3xl font-black text-white">Help & Contact</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="metric-card">
                <div class="text-sm text-[#eedcbbbf]">Registered Students</div>
                <div class="mt-3 text-3xl font-black text-white">{{ $stats['students'] }}</div>
            </div>
            <div class="metric-card">
                <div class="text-sm text-[#eedcbbbf]">Marketplace Listings</div>
                <div class="mt-3 text-3xl font-black text-white">{{ $stats['listings'] }}</div>
            </div>
            <div class="metric-card">
                <div class="text-sm text-[#eedcbbbf]">Completed Deals</div>
                <div class="mt-3 text-3xl font-black text-white">{{ $stats['completed'] }}</div>
            </div>
            <div class="metric-card">
                <div class="text-sm text-[#eedcbbbf]">Departments Covered</div>
                <div class="mt-3 text-3xl font-black text-white">{{ $stats['departments'] }}</div>
            </div>
        </section>

        <section class="space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-kicker">Recent Listings</p>
                    <h2 class="section-title mt-2">Fresh academic items from UM students</h2>
                </div>
                <a href="{{ route('marketplace.index') }}" class="btn-secondary">See All Listings</a>
            </div>

            @if($recentItems->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($recentItems as $item)
                        <x-item-card :item="$item" />
                    @endforeach
                </div>
            @else
                <div class="glass-card px-6 py-12 text-center">
                    <h3 class="text-xl font-bold text-white">No listings yet</h3>
                    <p class="mt-3 text-sm text-[#eedcbbcc]">Post the first item to start marketplace activity.</p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
