<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-[#0093DD] border-b border-[#0080C0]">
        <h3 class="text-base font-semibold text-white flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ __('Profile Information') }}
        </h3>
        <p class="mt-1 text-sm text-blue-50">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="p-6">
        @csrf
        @method('patch')

        <div class="space-y-4 max-w-md">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                       value="{{ old('email', $user->email) }}" required autocomplete="username">
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-yellow-900 hover:text-yellow-700 font-medium">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-700">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2 bg-[#0093DD] text-white text-sm font-medium rounded-md hover:bg-[#0080C0] transition shadow-sm">
                    {{ __('Save') }}
                </button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                       class="text-sm text-green-600 font-medium">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </div>
    </form>
</div>
