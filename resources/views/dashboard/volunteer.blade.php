<x-layout>
    <div class="bg-white mx-auto p-6 rounded-2xl shadow-md w-full md:max-w-6xl border border-gray-100 mt-10 space-y-6">

        {{-- Top Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 bg-gray-50 rounded-xl border text-center">
                <p class="text-gray-500 text-sm">Applied Events</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $appliedEvents->count() }}</p>
            </div>
            <div class="p-5 bg-gray-50 rounded-xl border text-center">
                <p class="text-gray-500 text-sm">Saved Events</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $savedEvents->count() }}</p>
            </div>
            <div class="p-5 bg-gray-50 rounded-xl border text-center">
                <p class="text-gray-500 text-sm">Profile Status</p>
                <p class="text-sm font-semibold text-amber-500">
                    @if($user->bio && $user->skills)
                        <span class="text-emerald-600">Complete</span>
                    @else
                        <span class="text-amber-500">Incomplete</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Main Section --}}
        <div class="grid md:grid-cols-3 gap-6">

            {{-- Left side: Profile card --}}
            <div class="p-6 bg-gray-50 rounded-xl border text-center space-y-4">
                <img src="{{ $user->avatar_url }}" 
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 rounded-full mx-auto">

                <div>
                    <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                </div>

                {{-- Bio Preview --}}
                <p class="text-emerald-600 text-sm">About me</p>
                <div class="text-sm text-gray-600">
                    @if($user->bio)
                        <p>{{ Str::limit($user->bio, 100) }}</p>
                    @else
                        <p class="text-amber-500">Add a bio to improve your profile</p>
                    @endif
                </div>

                {{-- Skills Preview --}}
                <p class="text-emerald-600 text-sm">My skills</p>
                <div class="text-sm text-gray-600">
                    @if($user->skills)
                        <div class="flex flex-wrap gap-2 justify-center">
                            @foreach(explode(',', $user->skills) as $skill)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full">
                                    {{ trim($skill) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-amber-500">Add skills to help organizers know you</p>
                    @endif
                </div>
            </div>

            {{--  Right side: Edit profile --}}
            <div class="md:col-span-2 p-6 bg-gray-50 rounded-xl border space-y-4">

                <h2 class="text-lg font-semibold text-emerald-600">Edit Your Profile</h2>
                @if (session('status') === 'profile-updated')
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        Profile updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <x-inputs.text name="name" label="Full Name" :value="$user->name" placeholder="Use your real name (visible to organizers)" />
                    </div>

                    {{-- Bio --}}
                    <div>
                        <x-inputs.text-area 
                            name="bio"
                            label="Bio"
                            :value="$user->bio"
                            placeholder="Tell organizers about yourself..."
                            rows="3"
                        />
                    </div>

                    {{-- Skills --}}
                    <div>
                        <x-inputs.text-area 
                            name="skills"
                            label="Skills"
                            :value="$user->skills"
                            placeholder="List your skills separated by commas (e.g. First Aid, Event Planning, Social Media)"
                            rows="3"
                        />
                    </div>

                    {{-- Avatar --}}
                    <div>
                        <x-inputs.file 
                            name="avatar"
                            label="Change Avatar"
                        />
                    </div>

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

        {{-- Applied Events --}}
        <div class="p-5 bg-gray-50 rounded-xl border">
            <h2 class="text-lg font-semibold text-emerald-600 mb-4">Applied Events</h2>

            @if($appliedEvents->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($appliedEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't applied for any events yet.</p>
            @endif
        </div>

        {{-- Saved Events --}}
        <div class="p-5 bg-gray-50 rounded-xl border">
            <h2 class="text-lg font-semibold text-emerald-600 mb-4">Saved Events</h2>

            @if($savedEvents->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($savedEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't saved any events yet.</p>
            @endif
        </div>

        {{-- Calendar Placeholder --}}
        <div class="p-5 bg-gray-50 rounded-xl border">
            <p class="text-lg font-semibold text-emerald-600 mb-4">Upcoming Calendar (Placeholder)</p>
            <p class="text-gray-500">Display a small calendar or list of upcoming events.</p>
        </div>

    </div>
</x-layout>