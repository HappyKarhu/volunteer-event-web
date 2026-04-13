<x-layout>
    <div class="mx-auto mt-10 w-full max-w-6xl space-y-8 px-5">
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-md">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-amber-400 px-6 py-10 md:px-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-5">
                        <img
                            src="{{ $user->avatar_url }}"
                            alt="{{ $user->name }}"
                            class="h-24 w-24 rounded-full border-4 border-white/70 bg-white object-cover shadow-lg md:h-28 md:w-28"
                        >

                        <div class="text-white">
                            <p class="mb-2 inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]">
                                Volunteer
                            </p>
                            <h1 class="text-3xl font-bold md:text-4xl">{{ $user->name }}</h1>
                            <p class="mt-2 max-w-2xl text-sm text-emerald-50 md:text-base">
                                Show what you care about, highlight your strengths, and keep your opportunities close by.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl bg-white/15 px-5 py-4 text-white backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-50">Applied</p>
                            <p class="mt-2 text-3xl font-bold">{{ $appliedEvents->count() }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/15 px-5 py-4 text-white backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-50">Saved</p>
                            <p class="mt-2 text-3xl font-bold">{{ $savedEvents->count() }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/15 px-5 py-4 text-white backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-50">Profile</p>
                            <p class="mt-2 text-lg font-semibold">
                                {{ $user->bio && $user->skills ? 'Complete' : 'In Progress' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-3 md:p-8">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">
                        <h2 class="mb-4 text-xl font-semibold text-emerald-600">Volunteer</h2>

                        <div class="space-y-4 text-sm text-gray-700">
                            <div>
                                <p class="mb-1 font-semibold text-gray-900">Email</p>
                                <p>{{ $user->email }}</p>
                            </div>

                            <div>
                                <p class="mb-1 font-semibold text-gray-900">About Me</p>
                                @if($user->bio)
                                    <p class="leading-6 text-gray-700">{{ Str::limit($user->bio, 160) }}</p>
                                @else
                                    <p class="text-amber-500">Add a bio so organizers can understand you quickly.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="mb-3 text-xl font-semibold text-emerald-600">Skills</h2>

                        @if($user->skills)
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $user->skills) as $skill)
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-amber-500">Add skills to help organizers know where you can help.</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6 md:col-span-2">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-emerald-600">Edit Volunteer Profile</h2>

                        @if (session('status') === 'profile-updated')
                            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                Profile updated successfully.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                            @csrf
                            @method('PUT')

                            <x-inputs.text name="name" label="Full Name" :value="$user->name" placeholder="Use your real name (visible to organizers)" />

                            <x-inputs.text-area
                                name="bio"
                                label="Bio"
                                :value="$user->bio"
                                placeholder="Tell organizers about yourself, what motivates you, and how you like to help..."
                                rows="4"
                            />

                            <x-inputs.text-area
                                name="skills"
                                label="Skills"
                                :value="$user->skills"
                                placeholder="List your skills separated by commas, for example: First Aid, Teamwork, Event Setup"
                                rows="4"
                            />

                            <x-inputs.file
                                name="avatar"
                                label="Change Avatar"
                            />

                            <div class="flex flex-wrap gap-3 pt-2">
                                <button class="rounded-xl bg-emerald-600 px-5 py-2 text-white shadow transition hover:bg-amber-500">
                                    Save Changes
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2 text-white shadow transition hover:bg-emerald-600">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 bg-white p-6 md:p-8">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-emerald-600">Applied Events</h2>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                        {{ $appliedEvents->count() }} {{ Str::plural('event', $appliedEvents->count()) }}
                    </span>
                </div>

                @if($appliedEvents->count())
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($appliedEvents as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-emerald-200 bg-emerald-50/50 px-6 py-8 text-center text-gray-600">
                        You haven't applied for any events yet.
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-100 bg-gray-50/60 p-6 md:p-8">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-emerald-600">Saved Events</h2>
                    <span class="rounded-full bg-white px-3 py-1 text-sm font-medium text-emerald-700 shadow-sm">
                        {{ $savedEvents->count() }} {{ Str::plural('event', $savedEvents->count()) }}
                    </span>
                </div>

                @if($savedEvents->count())
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($savedEvents as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-emerald-200 bg-white px-6 py-8 text-center text-gray-600">
                        You haven't saved any events yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
