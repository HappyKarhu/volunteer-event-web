<x-auth-layout :title="'Register to Volunteerio'" :subtitle="'Choose your role and create an account!'">
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Role Selection as Cards --}}
        <div>
            <x-input-label :value="__('Register as')" />
            <div class="grid md:grid-cols-2 gap-4 mt-2">

                {{-- Volunteer --}}
                <label class="cursor-pointer border rounded-xl p-4 hover:border-emerald-500 transition flex items-start gap-3 {{ old('role') == 'volunteer' ? 'border-emerald-600 bg-gray-50' : '' }}">
                    <input type="radio" name="role" value="volunteer" class="hidden" {{ old('role') == 'volunteer' ? 'checked' : '' }}>
                    <div class="text-3xl">🤝</div>
                    <div>
                        <p class="font-semibold text-gray-800">Volunteer</p>
                        <p class="text-sm text-gray-500">Join events, help your community, and track your volunteer hours.</p>
                    </div>
                </label>

                {{-- Organizer --}}
                <label class="cursor-pointer border rounded-xl p-4 hover:border-emerald-500 transition flex items-start gap-3 {{ old('role') == 'organizer' ? 'border-emerald-600 bg-gray-50' : '' }}">
                    <input type="radio" name="role" value="organizer" class="hidden" {{ old('role') == 'organizer' ? 'checked' : '' }}>
                    <div class="text-3xl">📋</div>
                    <div>
                        <p class="font-semibold text-gray-800">Organizer</p>
                        <p class="text-sm text-gray-500">Create volunteer events, manage teams, and track participation.</p>
                    </div>
                </label>

            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-amber-500 text-white font-semibold py-3 rounded-lg shadow transition">
                {{ __('Register') }}
            </button>
        </div>

        {{-- Login Link --}}
        <p class="text-center text-gray-500 mt-4">
            Already registered? <a href="{{ route('login') }}" class="text-emerald-600 hover:text-amber-500 font-semibold">Login</a>
        </p>
    </form>
</x-auth-layout>