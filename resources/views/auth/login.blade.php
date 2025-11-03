<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white rounded-lg shadow-md p-6">
        <!-- Pengingat email admin percobaan -->
        <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">
            <strong>Email admin percobaan:</strong> admin@bpsapp.com
            <strong>Password:</strong> 123456789
        </div>
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <h2 class="text-2xl font-semibold text-center mb-6 text-[#0093DD]">Masuk ke Akun Anda</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full border rounded-md shadow-sm focus:border-[#0093DD] focus:ring focus:ring-[#0093DD] focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full border rounded-md shadow-sm focus:border-[#0093DD] focus:ring focus:ring-[#0093DD] focus:ring-opacity-50" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#0093DD] shadow-sm focus:ring-[#0093DD]" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0093DD]" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="bg-[#0093DD] hover:bg-[#0077B6] text-white">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-[#0093DD] hover:underline">Daftar di sini</a></p>
        </div>
    </div>
</x-guest-layout>
