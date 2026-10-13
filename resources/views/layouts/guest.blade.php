<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-surface font-sans text-ink antialiased md:bg-card">
        <x-splash />

        {{-- Mobile: putih polos. Desktop: panel putih di atas latar abu muda. --}}
        <div class="flex min-h-screen items-center justify-center px-6 py-10">
            <div class="w-full max-w-sm md:rounded-lg md:bg-surface md:p-8 md:shadow-sm">
                <a href="/" class="flex justify-center">
                    <x-application-logo class="h-28 w-28 md:h-24 md:w-24" alt="" />
                </a>

                <div class="mt-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
