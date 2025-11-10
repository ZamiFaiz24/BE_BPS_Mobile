<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-[#0093DD] border-b border-[#0080C0]">
        <h3 class="text-base font-semibold text-white flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Update Password
        </h3>
            <p class="mt-1 text-sm text-blue-100">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="p-6">
        @csrf
        @method('put')

        <div class="space-y-4 max-w-md">
            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Current Password
                </label>
                <input id="update_password_current_password" name="current_password" type="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                       autocomplete="current-password">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>
                <input id="update_password_password" name="password" type="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                       autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                       autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2 bg-[#0093DD] text-white text-sm font-medium rounded-md hover:bg-[#0080C0] transition shadow-sm">
                    Save
                </button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                       class="text-sm text-green-600 font-medium">
                        Saved.
                    </p>
                @endif
            </div>
        </div>
    </form>
</div>
