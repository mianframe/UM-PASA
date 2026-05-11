<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UM-Pasa') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=auto" rel="stylesheet">

        <script>
            (() => {
                const savedTheme = localStorage.getItem('um-pasa-theme');
                const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
                const theme = savedTheme || (prefersLight ? 'light' : 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            })();

            window.toggleTheme = function toggleTheme() {
                const root = document.documentElement;
                const current = root.getAttribute('data-theme') || 'dark';
                const next = current === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                localStorage.setItem('um-pasa-theme', next);
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: next }));
            };
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-[Inter] antialiased">
        <div class="site-shell px-4 py-10">
            <div class="page-wrap grid min-h-[calc(100vh-5rem)] items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="liquid-rise space-y-8">
                    <div class="inline-flex items-center gap-3 rounded-full border border-[#f3df3230] bg-[#f3df3210] px-5 py-2 text-xs font-bold uppercase tracking-[0.28em] text-[#f3df32]">
                        University of Mindanao
                    </div>
                    <div class="space-y-4">
                        <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">UM-Pasa</h1>
                        <p class="max-w-2xl text-base leading-8 text-[#eedcbbd0] sm:text-lg">
                            Create an account, browse listings, request items, message sellers, and complete transactions in one student marketplace.
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="glass-card p-5">
                            <div class="text-sm font-semibold text-white">Student Access</div>
                            <p class="mt-2 text-sm text-[#eedcbbcc]">Registration accepts UM email accounts only.</p>
                        </div>
                        <div class="glass-card p-5">
                            <div class="text-sm font-semibold text-white">Marketplace Flow</div>
                            <p class="mt-2 text-sm text-[#eedcbbcc]">Sell, rent, or request items in a few clicks.</p>
                        </div>
                        <div class="glass-card p-5">
                            <div class="text-sm font-semibold text-white">Help and Contact</div>
                            <p class="mt-2 text-sm text-[#eedcbbcc]">See instructions and Contact Us details if you need help using the system.</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card liquid-rise overflow-hidden px-6 py-8 shadow-2xl sm:px-8">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-red-700 to-red-950 text-lg font-black text-white">
                            <img src="{{ asset('UMPASALOGO.png') }}" alt="UM-Pasa logo" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <div class="text-lg font-black tracking-[0.18em] text-white">UM-PASA</div>
                            <div class="text-sm text-[#eedcbbcc]">Academic Resource Marketplace</div>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </body>
</html>
