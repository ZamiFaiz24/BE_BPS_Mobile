<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
            <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                {{ __('Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-password-form')
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>