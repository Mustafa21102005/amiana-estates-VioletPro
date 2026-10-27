<x-guest-layout>
    <x-slot name="title">Sign in</x-slot>

    <h1 class="font-serif text-4xl text-navy dark:text-ivory">Welcome back</h1>
    <div class="mt-4 h-px w-12 bg-gold"></div>
    <p class="mt-4 text-sm text-charcoal/70 dark:text-ivory/70">Sign in to manage Amiana Estates.</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded-sm border-mist/60 text-navy focus:ring-gold dark:border-mist/40 dark:bg-white/5 dark:text-gold">
            <span class="ms-2 text-sm text-charcoal/70 dark:text-ivory/70">{{ __('Remember me') }}</span>
        </label>

        <x-primary-button class="w-full">{{ __('Sign in') }}</x-primary-button>
    </form>
</x-guest-layout>
