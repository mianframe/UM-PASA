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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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

            <main class="liquid-rise pb-20 pt-8">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>
    </body>
</html>
