@props(['event'])

<div x-data="{ showAuthModal: false }" class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    
    {{-- Event Photo --}}
    @if($event->photo)
        <img src="{{ asset('storage/' . $event->photo) }}" 
            alt="{{ $event->title }}" 
            class="h-40 w-full object-cover">
    @endif

    {{-- Content --}}
    <div class="flex flex-1 flex-col justify-between p-5">
        <div>
            <div class="mb-3 flex items-start justify-between gap-3">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">
                    {{ $event->is_free ? 'Free Event' : 'Paid Event' }}
                </p>

                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                    {{ $event->applications->where('status', 'approved')->count() }} joined
                </span>
            </div>

            {{-- Event Title --}}
            <h2 class="mb-2 text-xl font-semibold text-gray-900 transition-colors duration-200 hover:text-amber-500">
                {{ $event->title }}
            </h2>

            {{-- Event Dates --}}
            <p class="mb-2 text-sm text-gray-500">
                {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
            </p>

            {{-- Description --}}
            <p class="mb-4 text-sm leading-6 text-gray-700">{{ Str::limit($event->description, 110) }}</p>

            <div class="space-y-2 text-sm text-gray-600">
                <p>
                    <span class="font-semibold text-gray-900">Location:</span> {{ $event->location ?? 'TBD' }}
                </p>
                <p>
                    <span class="font-semibold text-gray-900">Capacity:</span> {{ $event->capacity ?? 'Unlimited' }}
                </p>
                <p>
                    <span class="font-semibold text-gray-900">Price:</span>
                    @if($event->is_free)
                        <span class="font-semibold text-emerald-600">Free</span>
                    @else
                        <span class="font-semibold text-amber-600">${{ number_format($event->price, 2) }}</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Bottom Buttons: Details + Save/Unsave --}}
        <div class="mt-5 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">

            {{-- Details Button --}}
            <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm text-white shadow transition hover:bg-amber-500">
                <i class="fa-solid fa-circle-info"></i>
                Details
            </a>

            {{-- Save/Unsave Button (Volunteers and Guests Only) --}}
            @auth
                @if(auth()->user()->isVolunteer())
                    <form action="{{ auth()->user()->savedEvents->contains($event) ? route('events.unsave', $event) : route('events.save', $event) }}" method="POST">
                        @csrf
                        @if(auth()->user()->savedEvents->contains($event))
                            @method('DELETE')
                            <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl border border-amber-500 bg-amber-500 px-3 py-2 text-sm text-white transition hover:bg-amber-600">
                                <i class="fa-solid fa-bookmark"></i>
                                Saved
                            </button>
                        @else
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm text-emerald-600 shadow-sm ring-1 ring-emerald-200 transition hover:bg-emerald-50">
                                <i class="fa-regular fa-bookmark"></i>
                                Save
                            </button>
                        @endif
                    </form>
                @endif
            @endauth

            @guest
                <button type="button"
                    @click="showAuthModal = true"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm text-emerald-600 shadow-sm ring-1 ring-emerald-200 transition hover:bg-emerald-50">
                    <i class="fa-regular fa-bookmark"></i>
                    Save
                </button>
            @endguest
        </div>
    </div>

    {{-- Save/Unsave Button Guest redirected to login/register --}}
    @guest
        <div
            x-show="showAuthModal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        >
            <div
                @click.away="showAuthModal = false"
                class="bg-emerald-50 rounded-2xl p-6 w-full max-w-md shadow-lg"
            >
                <h2 class="text-xl font-semibold mb-3">You need to be logged in</h2>

                <p class="text-gray-600 mb-4">
                    To save events, please log in or create an account first.
                </p>

                <div class="flex gap-3">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg bg-emerald-600 text-white hover:bg-amber-500">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded shadow border-2 border-emerald-600 text-emerald-600 hover:bg-amber-500 hover:text-white transition transform hover:scale-105 cursor-pointer">
                        Register
                    </a>
                </div>

                <button
                    type="button"
                    @click="showAuthModal = false"
                    class="mt-4 text-sm text-gray-500 underline w-full"
                >
                    Cancel
                </button>
            </div>
        </div>
    @endguest
</div>
