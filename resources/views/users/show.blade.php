<x-layout>
    <div class="mx-auto mt-10 w-full max-w-6xl space-y-8 px-5">
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-md">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-amber-400 px-6 py-10 md:px-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-5">
                        <img
                            src="{{ $user->profile_image_url }}"
                            alt="{{ $user->name }}"
                            class="h-24 w-24 rounded-full border-4 border-white/70 bg-white object-cover shadow-lg md:h-28 md:w-28"
                        >

                        <div class="text-white">
                            <p class="mb-2 inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]">
                                {{ $user->isOrganizer() ? 'Organizer' : 'Volunteer' }}
                            </p>
                            <h1 class="text-3xl font-bold md:text-4xl">{{ $user->name }}</h1>

                            @if($user->company_name)
                                <p class="mt-2 text-sm text-emerald-50 md:text-base">{{ $user->company_name }}</p>
                            @endif
                        </div>
                    </div>

                    <a
                        href="{{ route('events.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 font-semibold text-emerald-700 shadow transition hover:bg-emerald-50"
                    >
                        <i class="fa fa-calendar"></i>
                        Browse Events
                    </a>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-3 md:p-8">
                <div class="space-y-6 md:col-span-2">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">
                        <h2 class="mb-3 text-xl font-semibold text-emerald-600">
                            {{ $user->isOrganizer() ? 'About the Organizer' : 'About the Volunteer' }}
                        </h2>

                        @if($user->bio)
                            <p class="leading-7 text-gray-700">{{ $user->bio }}</p>
                        @else
                            <p class="text-gray-500">
                                {{ $user->isOrganizer() ? 'This organizer has not added a bio yet.' : 'This volunteer profile will be available later.' }}
                            </p>
                        @endif
                    </div>

                    @if($user->isOrganizer())
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-semibold text-emerald-600">Events by {{ $user->name }}</h2>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                                    {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                                </span>
                            </div>

                            @if($events->isEmpty())
                                <p class="text-gray-500">No public events from this organizer yet.</p>
                            @else
                                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-2">
                                    @foreach($events as $event)
                                        <x-event-card :event="$event" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <h2 class="mb-3 text-xl font-semibold text-emerald-600">Volunteer Profile</h2>
                            <p class="text-gray-500">
                                Public volunteer profile details will be expanded later when volunteer applications are connected.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">
                        <h2 class="mb-4 text-xl font-semibold text-emerald-600">Contact & Info</h2>

                        <div class="space-y-4 text-sm text-gray-700">
                            @if($user->isOrganizer())
                                @if($user->contact_email)
                                    <div>
                                        <p class="mb-1 font-semibold text-gray-900">Contact Email</p>
                                        <a href="mailto:{{ $user->contact_email }}" class="text-emerald-600 hover:text-amber-500">
                                            {{ $user->contact_email }}
                                        </a>
                                    </div>
                                @endif

                                @if($user->website)
                                    <div>
                                        <p class="mb-1 font-semibold text-gray-900">Website</p>
                                        <a href="{{ $user->website }}" target="_blank" class="break-all text-emerald-600 hover:text-amber-500">
                                            {{ $user->website }}
                                        </a>
                                    </div>
                                @endif

                                @if($user->phone)
                                    <div>
                                        <p class="mb-1 font-semibold text-gray-900">Phone</p>
                                        <p>{{ $user->phone }}</p>
                                    </div>
                                @endif
                            @endif

                            @if($user->isVolunteer())
                                <div>
                                    <p class="mb-1 font-semibold text-gray-900">Email</p>
                                    <p>{{ $user->email }}</p>
                                </div>

                                @if($user->skills)
                                    <div>
                                        <p class="mb-2 font-semibold text-gray-900">Skills</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(explode(',', $user->skills) as $skill)
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                                    {{ trim($skill) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if(!$user->contact_email && !$user->website && !$user->phone && !$user->skills && !$user->isVolunteer())
                                <p class="text-gray-500">This organizer has not shared extra contact information yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
