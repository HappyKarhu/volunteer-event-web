<x-layout>
    <div class="container mx-auto mt-10 px-5">
        {{-- Organizer Info --}}
        <div class="flex items-center space-x-6 mb-8">
            @if($user->role === 'organizer' && $user->logo)
                <img src="{{ asset('images/logos/' . $user->logo) }}" 
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 object-contain rounded-full border shadow-sm">
            @else
                <img src="{{ asset('images/avatars/' . ($user->logo ?? 'default-avatar.png')) }}" 
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 object-contain rounded-full border shadow-sm">
            @endif
            <div>
                <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                @if($user->company_name)
                    <p class="text-gray-600">{{ $user->company_name }}</p>
                @endif
                @if($user->bio)
                    <p class="text-gray-700 mt-2">{{ $user->bio }}</p>
                @endif
                <div class="mt-2 text-sm text-gray-500 space-x-4">
                    @if($user->website)
                        <a href="{{ $user->website }}" target="_blank" class="hover:underline text-emerald-600">Website</a>
                    @endif
                    @if($user->email)
                        <a href="mailto:{{ $user->email }}" class="hover:underline text-emerald-600">Email</a>
                    @endif
                    @if($user->phone)
                        <span>Phone: {{ $user->phone }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Organizer Events --}}
        <h2 class="text-xl font-semibold mb-4">Events by {{ $user->name }}</h2>

        @if($events->isEmpty())
            <p class="text-gray-600">No events available from this organizer yet.</p>
        @else
            <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                @foreach($events as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>
        @endif

        {{-- Back Button --}}
        <div>
            <br>
            <a href="{{ route('events.index') }}" 
            icon="person-walking-arrow-loop-left"
               class="inline-block bg-white hover:shadow-lg transition hover:bg-emerald-50 text-gray-800 py-2 px-4 rounded transition">
               &larr; Back to Events
            </a>
        </div>
    </div>
</x-layout>