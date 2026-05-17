<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="section-kicker">About Us</p>
            <h1 class="section-title">The UM-Pasa team</h1>
            <p class="section-copy">UM-Pasa is a student marketplace system built for university-focused buying, selling, renting, messaging, payments, and transaction tracking.</p>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white">System Purpose</h2>
                <p class="mt-4 text-sm leading-7 text-[#eedcbbcc]">
                    UM-Pasa helps UM students post academic items, request sale or rental transactions, coordinate safely through messages, upload payment proof, and monitor each transaction from request to completion.
                </p>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Focus</div>
                        <div class="mt-2 font-semibold text-white">University marketplace</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Users</div>
                        <div class="mt-2 font-semibold text-white">UM students and admins</div>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white">Publishers / Developers</h2>
                <div class="mt-4 space-y-3 text-sm text-[#eedcbbcc]">
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">PASA Development Team</div>
                        <div class="mt-1">Student developers and system publishers of UM-Pasa.</div>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Project Role</div>
                        <div class="mt-1">Designed and implemented the marketplace, rental flow, admin approval, reports, and transaction tracking features.</div>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Institution</div>
                        <div class="mt-1">University of Mindanao</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="landing-section feature-grid">
            <div class="feature-card">
                <span>01</span>
                <h3>Student-centered</h3>
                <p>Built around academic items, university handoffs, and UM email accounts.</p>
            </div>
            <div class="feature-card">
                <span>02</span>
                <h3>Traceable deals</h3>
                <p>Every request has a transaction record, payment mode, proof status, and tracking state.</p>
            </div>
            <div class="feature-card">
                <span>03</span>
                <h3>Admin moderated</h3>
                <p>Listed items require approval and rejected listings can include a reason.</p>
            </div>
            <div class="feature-card">
                <span>04</span>
                <h3>Sale and rental ready</h3>
                <p>Supports sale listings, rental ranges, daily rates, due dates, and reminders.</p>
            </div>
        </div>
    </div>
</x-app-layout>
