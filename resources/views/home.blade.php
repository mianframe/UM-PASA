<x-app-layout>
    <div class="landing-wrap page-wrap space-y-16">
        <section class="landing-hero">
            <div class="landing-glow landing-glow-red"></div>
            <div class="landing-glow landing-glow-gold"></div>

            <div class="landing-hero-grid">
                <div class="landing-hero-copy">
                    <p class="section-kicker">University Marketplace</p>
                    <div class="space-y-5">
                        <h1 class="landing-title">
                            Find, sell, and rent campus items in one trusted <span class="title-accent">UM</span> marketplace.
                        </h1>
                        <p class="landing-copy">
                            UM-Pasa helps students browse academic items, manage listings, coordinate with sellers, and track campus transactions in one organized system.
                        </p>
                    </div>
                    <div class="landing-actions">
                        <a href="{{ route('marketplace.index') }}" class="btn-primary">
                            <svg class="btn-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12l-1 13H7L6 7Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7a3 3 0 0 1 6 0"></path>
                            </svg>
                            Browse Marketplace
                            <span class="btn-arrow">›</span>
                        </a>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn-secondary">
                                <svg class="btn-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"></path>
                                </svg>
                                Post a Listing
                                <span class="btn-arrow">›</span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-secondary">
                                <svg class="btn-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 19a4 4 0 0 0-8 0"></path>
                                    <circle cx="12" cy="9" r="3"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6M16 11h6"></path>
                                </svg>
                                Create Student Account
                                <span class="btn-arrow">›</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="hero-preview-wrap">
                    <div class="hero-flow-card">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.24em] text-[#f3df32]">System Characteristics</div>
                                <div class="mt-3 text-2xl font-extrabold leading-tight text-white">A moderated marketplace for campus sale and rental transactions.</div>
                            </div>
                            <div class="hero-flow-mark">UM</div>
                        </div>

                        <div class="flow-steps">
                            <div class="flow-step">
                                <div class="flow-icon flow-icon-gold">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 12 12 4H5v7l8 8 7-7Z"></path><path d="M8.5 8.5h.01"></path></svg>
                                </div>
                                <span>01</span>
                                <div>
                                    <strong>Admin-reviewed listings</strong>
                                    <p>Listings are checked before they appear publicly.</p>
                                </div>
                            </div>
                            <div class="flow-step">
                                <div class="flow-icon flow-icon-red">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.3 8.8 8.8 0 0 1-3.8-.9L3 20l1.2-4.6A8 8 0 1 1 21 11.5Z"></path></svg>
                                </div>
                                <span>02</span>
                                <div>
                                    <strong>Flexible payments</strong>
                                    <p>Sellers choose accepted payment methods for each item.</p>
                                </div>
                            </div>
                            <div class="flow-step">
                                <div class="flow-icon flow-icon-gold">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-8 0v2"></path><circle cx="13" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </div>
                                <span>03</span>
                                <div>
                                    <strong>Rental tracking</strong>
                                    <p>Rental duration, due date, and overdue status stay visible.</p>
                                </div>
                            </div>
                            <div class="flow-step">
                                <div class="flow-icon flow-icon-red">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path><path d="m9 12 2 2 4-5"></path></svg>
                                </div>
                                <span>04</span>
                                <div>
                                    <strong>Reports and receipts</strong>
                                    <p>Students and admins can generate clear transaction records.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="floating-mini-card mini-card-one">
                        <span class="mini-dot"></span>
                        <div>
                            <div class="font-bold text-white">UM Email Only</div>
                            <p>Campus-focused access</p>
                        </div>
                    </div>
                    <div class="floating-mini-card mini-card-two">
                        <span class="mini-dot mini-dot-gold"></span>
                        <div>
                            <div class="font-bold text-white">Safe Campus Meetups</div>
                            <p>Plan before handoff</p>
                        </div>
                    </div>
                    <div class="floating-mini-card mini-card-three">
                        <span class="mini-dot"></span>
                        <div>
                            <div class="font-bold text-white">Student Deals</div>
                            <p>Request with confidence</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-stats">
            <div class="premium-stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 11a4 4 0 1 0-8 0"></path><path d="M5 20a7 7 0 0 1 14 0"></path></svg>
                </div>
                <div class="stat-number">{{ $stats['students'] }}</div>
                <div class="stat-label">Registered Students</div>
                <p>Verified student accounts using the marketplace.</p>
            </div>
            <div class="premium-stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 7h16"></path><path d="M6 7v13h12V7"></path><path d="M9 11h6"></path></svg>
                </div>
                <div class="stat-number">{{ $stats['listings'] }}</div>
                <div class="stat-label">Marketplace Listings</div>
                <p>Academic items posted by UM students.</p>
            </div>
            <div class="premium-stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m5 12 4 4L19 6"></path><path d="M4 20h16"></path></svg>
                </div>
                <div class="stat-number">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed Deals</div>
                <p>Transactions completed through campus meetups.</p>
            </div>
            <a href="#departments-covered" class="premium-stat-card block">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 21h18"></path><path d="M5 21V8l7-4 7 4v13"></path><path d="M9 21v-6h6v6"></path></svg>
                </div>
                <div class="stat-number">{{ $stats['departments'] }}</div>
                <div class="stat-label">Departments Covered</div>
                <p>Programs and departments supported by filters.</p>
            </a>
        </section>

        <section id="departments-covered" class="landing-section glass-card p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-kicker">Departments Covered</p>
                    <h2 class="section-title mt-2">Departments and programs in UM-Pasa</h2>
                    <p class="section-copy mt-3 max-w-3xl">Browse by department and program when posting or filtering marketplace listings.</p>
                </div>
                <a href="{{ route('marketplace.index') }}" class="btn-secondary">Browse by Department</a>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($departments as $department => $programs)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <h3 class="font-bold text-white">{{ $department }}</h3>
                        @if($programs)
                            <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-300">
                                @foreach($programs as $program)
                                    <li>{{ $program }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-3 text-sm text-slate-300">General department-level listings.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="landing-section space-y-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-kicker">Recent Listings</p>
                    <h2 class="section-title mt-2">Fresh academic items from UM students</h2>
                    <p class="section-copy mt-3 max-w-2xl">Scan the newest student listings or jump into marketplace filters for category, department, and listing type.</p>
                </div>
                <a href="{{ route('marketplace.index') }}" class="btn-secondary">See All Listings</a>
            </div>

            <div class="preview-filter-bar">
                <a href="{{ route('marketplace.index') }}" class="preview-search">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3a6 6 0 1 0 4.472 10.001l3.763 3.764 1.414-1.414-3.764-3.763A6 6 0 0 0 9 3Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" /></svg>
                    <span>Search items</span>
                </a>
                <a href="{{ route('marketplace.index') }}" class="preview-filter-chip">Category</a>
                <a href="{{ route('marketplace.index') }}" class="preview-filter-chip">Department</a>
                <a href="{{ route('marketplace.index') }}" class="preview-filter-chip">Price type</a>
            </div>

            @if($recentItems->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($recentItems as $item)
                        <x-item-card :item="$item" />
                    @endforeach
                </div>
            @else
                <div class="empty-state-card">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 8h14l-1.2 12H6.2L5 8Z"></path><path d="M9 8a3 3 0 0 1 6 0"></path><path d="M9 13h6"></path></svg>
                    </div>
                    <h3>No listings yet</h3>
                    <p>Be the first student to post a calculator, book, uniform, gadget, or school supply.</p>
                    @auth
                        <a href="{{ route('items.create') }}" class="btn-primary">Create First Listing</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">Create First Listing</a>
                    @endauth
                </div>
            @endif
        </section>

        <section class="landing-section feature-grid">
            <div class="feature-card">
                <span>01</span>
                <h3>UM email accounts only</h3>
                <p>Keep marketplace activity focused around campus accounts and student needs.</p>
            </div>
            <div class="feature-card">
                <span>02</span>
                <h3>Buy, sell, and rent</h3>
                <p>Support flexible student deals for books, uniforms, calculators, gadgets, and supplies.</p>
            </div>
            <div class="feature-card">
                <span>03</span>
                <h3>Built-in messaging</h3>
                <p>Ask questions, send meetup proposals, and coordinate handoffs without leaving UM-Pasa.</p>
            </div>
            <div class="feature-card">
                <span>04</span>
                <h3>Organized campus marketplace</h3>
                <p>Filter by category, department, program, condition, and listing type to move fast.</p>
            </div>
        </section>
    </div>
</x-app-layout>
