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
                <h2 class="text-xl font-bold text-white">Project Information</h2>
                <div class="mt-4 space-y-3 text-sm text-[#eedcbbcc]">
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">PASA Development Team</div>
                        <div class="mt-1">Student developers and system publishers of UM-Pasa.</div>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Project Role</div>
                        <div class="mt-1">Designed and implemented the marketplace, rental flow, admin approval, reports, receipts, payment proof upload, and transaction tracking features.</div>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Institution</div>
                        <ul class="mt-2 space-y-1 text-[#eedcbbcc]">
                            <li>Department of Computing Education</li>
                            <li>Information Technology Program</li>
                            <li>2nd Year</li>
                            <li>UM Tagum College - Visayan Campus</li>
                            <li>Visayan Village, Tagum City, Davao del Norte</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-kicker">Project Team</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Group 1 Members</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-[#eedcbbcc]">UM-Pasa was developed by Group 1 for IT 9A - Professional Track for IT 3.</p>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach([
                    ['name' => 'Terante, Markpaul', 'role' => 'Project Leader'],
                    ['name' => 'Tuyac, Sophia Khym O.', 'role' => 'Front-end Developer'],
                    ['name' => 'Coronia, Ian Miguel T.', 'role' => 'Front-end Developer'],
                    ['name' => 'Bacunlay, Tejay R.', 'role' => 'Back-end Developer'],
                    ['name' => 'Nalzaro, Rhena Mae T.', 'role' => 'Back-end Developer'],
                    ['name' => 'Arapoc, Dongie Ana Marie A.', 'role' => 'Back-end Developer'],
                    ['name' => 'Villarosa, Jorlan', 'role' => 'Back-end Developer'],
                ] as $member)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-sm font-semibold text-white">{{ $member['name'] }}</div>
                        <div class="mt-2 text-xs font-bold uppercase tracking-[0.18em] text-red-200">{{ $member['role'] }}</div>
                    </div>
                @endforeach
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
