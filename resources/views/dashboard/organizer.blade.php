<x-layout>
    <div class="container mx-auto mt-10 px-5">

        {{-- Profile --}}
        <div class="bg-white p-6 rounded-2xl shadow-md mb-8 text-center">
            <img 
                src="{{ $user->avatar ? asset('storage/' . $user->logos) : asset('images/logos/logo-greenorg.png') }}"
                class="w-24 h-24 rounded-full mx-auto mb-3"
            >
            <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
        </div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <x-inputs.text 
                name="name" 
                label="Name" 
                :value="$user->name" 
            />

            <x-inputs.text 
                name="email" 
                label="Email" 
                :value="$user->email" 
            />

            {{-- Avatar Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Change Avatar</label>
                <input type="file" name="avatar" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <button class="w-full bg-emerald-600 hover:bg-amber-500 text-white py-2 rounded transition transform hover:scale-105">
                Update Profile
            </button>
        </form>

            {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" 
                class="w-full bg-emerald-600 hover:bg-amber-500 text-white py-2 rounded transition transform hover:scale-105">
                Logout
            </button>
        </form>


            {{-- Organizer Events --}}
            <h3 class="text-lg font-semibold mb-2">Your Events</h3>
            @if($events->count())
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($events as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't created any events yet.</p>
            @endif

        </div>
    </div>
</x-layout>