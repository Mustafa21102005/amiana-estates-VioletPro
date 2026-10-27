<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? trim($title) . ' | ' : '' }}{{ config('app.name') }}</title>

    @include('partials.assets')
</head>

<body class="bg-ivory font-sans text-charcoal antialiased dark:bg-navy dark:text-ivory">
    <div class="fixed right-4 top-4 z-20 sm:right-6 sm:top-6">
        <x-theme-toggle class="text-navy dark:text-ivory" />
    </div>

    <div class="min-h-screen lg:grid lg:grid-cols-2">

        {{-- Brand panel: always dark, so the logo is forced to the white version --}}
        <aside
            class="relative hidden flex-col items-center justify-center overflow-hidden bg-navy p-12 text-ivory lg:flex">
            <div class="absolute inset-0 bg-gradient-to-br from-navy via-navy to-[#0B2447]"></div>
            <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border border-gold/20"></div>
            <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full border border-gold/20"></div>

            <div class="relative flex flex-col items-center text-center">
                <a href="{{ url('/') }}"><x-application-logo on="dark" class="h-72" /></a>
                <div class="mt-10 h-px w-16 bg-gold"></div>
                <p class="mt-6 font-serif text-2xl text-ivory/90">Modern luxury living in Aberdeen</p>
            </div>

            <p class="absolute bottom-8 text-xs text-mist/70">&copy; {{ date('Y') }} Amiana Estates</p>
        </aside>

        {{-- Form panel: follows the theme, so the logo uses auto --}}
        <main class="flex min-h-screen flex-col items-center justify-center bg-ivory px-6 py-12 dark:bg-navy">
            <a href="{{ url('/') }}" class="mb-10 lg:hidden"><x-application-logo class="h-32" /></a>
            <div class="w-full max-w-sm">{{ $slot }}</div>
        </main>
    </div>
</body>

</html>
