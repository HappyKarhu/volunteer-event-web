<x-auth-layout :title="'Log in to Volunteerio'" :subtitle="'Welcome back! Please enter your credentials to access your account.'">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email Address --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email" 
                class="block mt-1 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                class="block mt-1 w-full" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="rounded border border-gray-300 bg-white text-emerald-600 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                >
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        {{-- Submit --}}
        <div>
            <button class="w-full bg-emerald-600 hover:bg-amber-500 text-white font-semibold py-3 rounded-lg shadow transition">
                {{ __('Log in') }}
            </button>
        </div>

        {{-- Forgot Password / Register Links --}}
        <div class="flex justify-between text-sm mt-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-emerald-600 hover:text-amber-500 font-semibold">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-amber-500 font-semibold">
                {{ __('Register') }}
            </a>
        </div>
    </form>
</x-auth-layout>