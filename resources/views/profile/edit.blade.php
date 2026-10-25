<x-app-layout>
    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('more') }}" class="text-sm text-slate-500 hover:text-ink">Lainnya</a>
        <span class="text-slate-300">/</span>
        <h1 class="text-xl font-semibold">Profil</h1>
    </div>

    @include('profile.partials.update-profile-information-form')
    @include('profile.partials.update-password-form')
    @include('profile.partials.delete-user-form')
</x-app-layout>
