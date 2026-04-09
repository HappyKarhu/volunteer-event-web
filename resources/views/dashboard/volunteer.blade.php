<x-layout>
    <div class="bg-white mx-auto p-6 rounded-2xl shadow-md w-full md:max-w-5xl border border-gray-100 mt-10 space-y-6">

        {{-- Top Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Applied Events</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $appliedEvents->count() }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Saved Events</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $savedEvents->count() }}</p>
            </div>
            {{-- 
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Upcoming Events</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $upcomingEvents->count() }}</p>
            </div>
            --}}
        </div>

        {{-- Top section: Left + Right --}}
        <div class="md:flex md:gap-6">

            {{-- Left Column: Profile Info --}}
            <div class="md:w-1/3 p-6 bg-gray-50 rounded-xl border border-gray-100 text-center space-y-3">
                <img src="{{ $user->avatar_url }}" 
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 rounded-full mx-auto">
                <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            </div>

            {{-- Right Column: Update Form + Logout --}}
            <div class="md:flex-1 p-6 bg-gray-50 rounded-xl border border-gray-100 space-y-3 mt-6 md:mt-0">
                <h2 class="text-lg font-semibold text-emerald-600 mb-3">Update Profile</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    @method('PUT')

                    <x-inputs.text name="name" label="Name" :value="$user->name" />
                    <x-inputs.text name="email" label="Email" :value="$user->email" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Change Avatar</label>
                        <input type="file" name="avatar" class="w-full px-3 py-1.5 border rounded-lg">
                    </div>

                    <button class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg
                        bg-emerald-600 text-white hover:bg-amber-500">
                        Update Profile
                    </button>
                </form>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg
                        bg-amber-500 text-white hover:bg-emerald-600">
                        Logout
                    </button>
                </form>
            </div>

        </div>

        {{-- Bottom Section: Applied Events --}}
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
            <h2 class="text-lg font-semibold text-emerald-600 mb-3">Applied Events</h2>
            @if($appliedEvents->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($appliedEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't applied for any events yet.</p>
            @endif
        </div>

        {{-- Bottom Section: Saved Events --}}
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
            <h2 class="text-lg font-semibold text-emerald-600 mb-3">Saved Events</h2>
            @if($savedEvents->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($savedEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't saved any events yet.</p>
            @endif
        </div>

        {{-- Calendar Placeholder --}}
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center text-gray-500">
            <p class="font-semibold mb-2">Upcoming Calendar (Placeholder)</p>
            <p>Display a small calendar or list of upcoming events.</p>
        </div>

    </div>
</x-layout>