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
        <div class="site-shell">
            @include('layouts.navigation')

            @isset($header)
                <header class="liquid-rise pt-8">
                    <div class="page-wrap">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="liquid-rise pb-28 pt-8">
                {{ $slot }}
            </main>

            <a href="{{ route('help') }}#contact-us" class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full border border-white/15 bg-red-700 text-white shadow-2xl transition hover:-translate-y-1 hover:bg-red-600" aria-label="Help and contact" title="Help and contact">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M9.5 9a2.7 2.7 0 1 1 4.3 2.2c-.9.6-1.8 1.3-1.8 2.8"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                </svg>
            </a>

            @include('layouts.footer')
        </div>
    </body>
</html>
