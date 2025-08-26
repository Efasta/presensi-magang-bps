<x-guest-layout>
    <div class="max-w-lg mx-auto py-5 border-x px-3 rounded-lg">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address or Name (User Credentials) -->
            <div>
                <x-input-label for="user_cred" :value="__('Email atau Nama lengkap')" />
                <x-text-input id="user_cred" class="block mt-1 w-full" type="text" name="user_cred" :value="old('user_cred')"
                    autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('user_cred')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded-sm border-gray-300 text-blue-600 shadow-xs focus:ring-blue-500"
                        name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa sandi?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
