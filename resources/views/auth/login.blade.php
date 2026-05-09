<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm uppercase tracking-[0.2em] text-red-200">Sign In</p>
        <h2 class="mt-2 text-3xl font-bold text-white">Access your UM-Pasa account</h2>
        <p class="mt-2 text-sm text-slate-300">Use your university email and password to continue.</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm text-emerald-200" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('University Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="student@umindanao.edu.ph" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/10 text-red-700 shadow-sm focus:ring-red-500" name="remember">
                <span class="ms-2 text-sm text-slate-300">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-2 sm:gap-4 sm:flex-row sm:items-center">
                @if (Route::has('password.request'))
                    <a class="text-sm text-slate-300 underline transition hover:text-white" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                @if (Route::has('register'))
                    <a class="text-sm text-slate-300 underline transition hover:text-white" href="{{ route('register') }}">
                        {{ __('Register') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
