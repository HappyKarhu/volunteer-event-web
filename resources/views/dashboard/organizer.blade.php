<x-layout>
    <div class="bg-white mx-auto p-6 rounded-2xl shadow-md w-full md:max-w-6xl border border-gray-100 mt-10 space-y-6">

        {{-- Top Stats (optional same style as volunteer) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 bg-gray-50 rounded-xl border text-center">
                <p class="text-gray-500 text-sm">Total Events</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $events->count() }}</p>
            </div>

            <div class="p-5 bg-gray-50 rounded-xl border text-center">
                <p class="text-gray-500 text-sm">Account Type</p>
                <p class="text-sm font-semibold text-emerald-600">Organizer</p>
            </div>

            <div class="p-5 bg-gray-50 rounded-xl border text-center">
                <p class="text-gray-500 text-sm">Profile Status</p>
                <p class="text-sm font-semibold text-amber-500">
                    @if($user->name && $user->website)
                        <span class="text-emerald-600">Complete</span>
                    @else
                        <span class="text-amber-500">Incomplete</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Main Section --}}
        <div class="grid md:grid-cols-3 gap-6">

            {{-- Left: Profile Card --}}
            <div class="p-6 bg-gray-50 rounded-xl border text-center space-y-4">

                <img src="{{ $user->logo_url }}" 
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 rounded-full mx-auto">

                <div>
                    <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->contact_email }}</p>
                </div>

                <div class="text-sm text-gray-600">
                    @if($user->website)
                        <p class="text-emerald-600">{{ $user->website }}</p>
                    @else
                        <p class="text-amber-500">Add your website</p>
                    @endif
                </div>

                {{-- Contact Info Preview --}}

                <div class="text-sm text-gray-600">
                    @if($user->contact_email)
                        <p class="text-emerald-600">{{ $user->contact_email }}</p>
                    @else
                        <p class="text-amber-500">Add a contact email</p>
                    @endif
                </div>
                
                <div class="text-sm text-gray-600">
                    @if($user->phone)
                        <p class="text-emerald-600">{{ $user->phone }}</p>
                    @else
                        <p class="text-amber-500">Add a contact phone number</p>
                    @endif
                </div>
                

                {{-- Bio Preview --}}
                <div class="text-sm text-gray-600">
                    @if($user->bio)
                        <p>{{ Str::limit($user->bio, 100) }}</p>
                    @else
                        <p class="text-amber-500">Add a bio to improve your profile</p>
                    @endif
                </div>

                

            </div>

            {{-- Right: Edit Form --}}
            <div class="md:col-span-2 p-6 bg-gray-50 rounded-xl border space-y-4">

                <h2 class="text-lg font-semibold text-emerald-600">Edit Organization Profile</h2>
                @if (session('status') === 'profile-updated')
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        Profile updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Company Name --}}
                    <x-inputs.text 
                        name="name"
                        label="Company Name"
                        :value="$user->name"
                        placeholder="Company name"
                    />

                    {{-- Contact Email --}}
                    <x-inputs.text 
                        name="contact_email"
                        label="Contact Email"
                        :value="$user->contact_email"
                        placeholder="Public contact email"
                    />

                    {{-- Website --}}
                    <x-inputs.text 
                        name="website"
                        label="Website"
                        :value="$user->website"
                        placeholder="https://company-website.com"
                    />

                    {{-- Phone --}}
                    <x-inputs.text 
                        name="phone"
                        label="Contact Phone"
                        :value="$user->phone"
                        placeholder="+1 234 567 890"
                    />
                    {{-- About the Company --}}
                    <div>
                        <x-inputs.text-area 
                            name="bio"
                            label="About Company"
                            :value="$user->bio"
                            placeholder="Tell volunteers about your company..."
                            rows="3"
                        />
                    </div>

                    {{-- Logo --}}
                    <x-inputs.file 
                        name="logo"
                        label="Change Logo"
                    />

                    <button class="px-5 py-2 rounded-lg shadow transition transform hover:scale-105
                        bg-emerald-600 text-white hover:bg-amber-500">
                        Save Changes
                    </button>
                </form>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="mt-2 px-5 py-2 rounded-lg shadow transition transform hover:scale-105
                        bg-amber-500 text-white hover:bg-emerald-600">
                        Logout
                    </button>
                </form>

            </div>
        </div>

        {{-- Bottom Section: Events --}}
        <div class="p-5 bg-gray-50 rounded-xl border">
            <h2 class="text-lg font-semibold text-emerald-600 mb-4">Your Events</h2>

            @if($events->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($events as $event)
                        <div class="relative">
                            <x-event-card :event="$event" />

                            <form action="{{ route('events.destroy', $event) }}" 
                                method="POST"
                                class="absolute top-2 right-2"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-3 py-2 rounded-xl shadow bg-white text-red-600 border border-red-300 hover:bg-red-50">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't created any events yet.</p>
            @endif
        </div>

    </div>
</x-layout>