<x-app-layout>
    <x-slot name="header">
        {{-- Gunakan header yang konsisten dengan halaman lain --}}
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
            <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                {{ __('Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        {{-- Ini sudah benar, dengan asumsi Anda sudah hapus wrapper card-in-card --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-password-form')
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>