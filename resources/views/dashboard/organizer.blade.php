<x-layout>
    <div class="bg-white mx-auto p-8 rounded-2xl shadow-md w-full md:max-w-5xl border border-gray-100 mt-10 space-y-6">

        {{-- Top section: Left + Right --}}
        <div class="md:flex md:gap-6">
            
            {{-- Left Column: Profile Info --}}
            <div class="md:w-1/3 p-6 bg-gray-50 rounded-xl border border-gray-100 text-center space-y-4">
                <img 
                    src="{{ $user->avatar ? asset('storage/' . $user->logos) : asset('images/logos/logo-greenorg.png') }}"
                    class="w-24 h-24 rounded-full mx-auto"
                >
                <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            </div>

            {{-- Right Column: Update Form + Logout --}}
            <div class="md:flex-1 p-6 bg-gray-50 rounded-xl border border-gray-100 space-y-4 mt-6 md:mt-0">
                <h2 class="text-lg font-semibold text-emerald-600 mb-4">Update Profile</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <x-inputs.text name="name" label="Name" :value="$user->name" />
                    <x-inputs.text name="email" label="Email" :value="$user->email" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Change Logo</label>
                        <input type="file" name="logos" class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <button class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg
                        bg-emerald-600 text-white hover:bg-amber-500">
                        Update Profile
                    </button>
                </form>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg
                        bg-amber-500 text-white hover:bg-emerald-600">
                        Logout
                    </button>
                </form>
            </div>

        </div>

        {{-- Bottom Section: Your Events --}}
        <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
            <h2 class="text-lg font-semibold text-emerald-600 mb-4">Your Events</h2>
            @if($events->count())
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($events as $event)
                        <div class="relative">
                            <x-event-card :event="$event" />

                            {{-- Delete Button --}}
                            <form action="{{ route('events.destroy', $event) }}" 
                                method="POST"
                                class="absolute top-2 right-2"
                                onsubmit="return confirm('Are you sure you want to delete this event?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-3 py-1 text-sm rounded-lg border border-red-400 text-red-600 
                                        bg-white hover:bg-red-50 hover:border-red-500 transition shadow">
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