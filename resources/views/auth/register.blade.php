<x-auth-layout :title="'Register to Volunteerio'" :subtitle="'Choose your role and create an account!'">
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Name --}}
        <div>
            <x-input-label for="name" value="Full Name (as you want it displayed)" />
            <x-inputs.text id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" placeholder="First and Last Name - required"  required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email') " />
            <x-inputs.text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Email Address - required" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label for="password" :value="__('Password')"  />
            <x-inputs.text id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Password - required" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" placeholder="Confirm Password - required" />
            <x-inputs.text id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Confirm Password - required" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Role Selection --}}
        <div x-data="{ role: '{{ old('role') }}', photo: null }" class="space-y-6">

            <x-input-label :value="__('Register as')" />
            <div class="grid md:grid-cols-2 gap-4 mt-2">

                {{-- Volunteer Card --}}
                <label 
                    class="cursor-pointer border rounded-xl p-4 flex items-start gap-3 transition"
                    :class="role === 'volunteer' ? 'border-emerald-600 bg-emerald-50' : 'border-gray-300 bg-white'">
                    <input type="radio" name="role" value="volunteer" class="hidden" x-model="role">

                    <div class="text-3xl">🤝</div> {{-- Emoji icon --}}
                    
                    <div>
                        <p class="font-semibold text-gray-800">Volunteer</p>
                        <p class="text-sm text-gray-500">Join events, help your community, and track your volunteer hours.</p>
                    </div>
                </label>

                {{-- Organizer Card --}}
                <label 
                    class="cursor-pointer border rounded-xl p-4 flex items-start gap-3 transition"
                    :class="role === 'organizer' ? 'border-emerald-600 bg-emerald-50' : 'border-gray-300 bg-white'">
                    <input type="radio" name="role" value="organizer" class="hidden" x-model="role">

                    <div class="text-3xl">📋</div> {{-- Emoji icon --}}
                    
                    <div>
                        <p class="font-semibold text-gray-800">Organizer</p>
                        <p class="text-sm text-gray-500">Create volunteer events, manage teams, and track participation.</p>
                    </div>
                </label>
            </div>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            {{-- Upload Photo/Logo --}}
            <div x-show="role" class="mt-4" x-data="{ preview: null }">
                <x-input-label for="photo" x-text="role === 'volunteer' ? 'Upload Avatar' : 'Upload Logo'"></x-input-label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full" @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">

                <div class="mt-2 w-24 h-24 rounded-full overflow-hidden" x-show="preview">
                    <img :src="preview" class="w-full h-full object-cover">
                </div>
            </div>

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