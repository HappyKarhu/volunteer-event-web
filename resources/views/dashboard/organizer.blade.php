<x-layout>
    <div class="mx-auto mt-10 w-full max-w-6xl space-y-8 px-5">
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-md">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-amber-400 px-6 py-10 md:px-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-5">
                        <img
                            src="{{ $user->logo_url }}"
                            alt="{{ $user->name }}"
                            class="h-24 w-24 rounded-full border-4 border-white/70 bg-white object-cover shadow-lg md:h-28 md:w-28"
                        >

                        <div class="text-white">
                            <p class="mb-2 inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]">
                                Organizer
                            </p>
                            <h1 class="text-3xl font-bold md:text-4xl">{{ $user->name }}</h1>
                            <p class="mt-2 max-w-2xl text-sm text-emerald-50 md:text-base">
                                Share your story, keep your profile up to date, and make your events easy to trust at a glance.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl bg-white/15 px-5 py-4 text-white backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-50">Events</p>
                            <p class="mt-2 text-3xl font-bold">{{ $events->count() }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/15 px-5 py-4 text-white backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-50">Profile</p>
                            <p class="mt-2 text-lg font-semibold">
                                {{ $user->name && $user->website ? 'Complete' : 'In Progress' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-3 md:p-8">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">
                        <h2 class="mb-4 text-xl font-semibold text-emerald-600">Organizer Snapshot</h2>

                        <div class="space-y-4 text-sm text-gray-700">
                            <div>
                                <p class="mb-1 font-semibold text-gray-900">Website</p>
                                @if($user->website)
                                    <a href="{{ $user->website }}" target="_blank" class="break-all text-emerald-600 hover:text-amber-500">
                                        {{ $user->website }}
                                    </a>
                                @else
                                    <p class="text-amber-500">Add your website</p>
                                @endif
                            </div>

                            <div>
                                <p class="mb-1 font-semibold text-gray-900">Contact Email</p>
                                @if($user->contact_email)
                                    <a href="mailto:{{ $user->contact_email }}" class="text-emerald-600 hover:text-amber-500">
                                        {{ $user->contact_email }}
                                    </a>
                                @else
                                    <p class="text-amber-500">Add a contact email</p>
                                @endif
                            </div>

                            <div>
                                <p class="mb-1 font-semibold text-gray-900">Phone</p>
                                @if($user->phone)
                                    <p>{{ $user->phone }}</p>
                                @else
                                    <p class="text-amber-500">Add a contact phone number</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="mb-3 text-xl font-semibold text-emerald-600">About the Organizer</h2>

                        @if($user->bio)
                            <p class="leading-7 text-gray-700">{{ Str::limit($user->bio, 180) }}</p>
                        @else
                            <p class="text-amber-500">Add a bio to help volunteers trust your organization faster.</p>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2 rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm space-y-4">
                    <h2 class="text-xl font-semibold text-emerald-600">Edit Organization Profile</h2>

                    @if (session('status') === 'profile-updated')
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            Profile updated successfully.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <x-inputs.text
                            name="name"
                            label="Company Name"
                            :value="$user->name"
                            placeholder="Company name"
                        />

                        <x-inputs.text
                            name="contact_email"
                            label="Contact Email"
                            :value="$user->contact_email"
                            placeholder="Public contact email"
                        />

                        <x-inputs.text
                            name="website"
                            label="Website"
                            :value="$user->website"
                            placeholder="company-website.com"
                        />

                        <x-inputs.text
                            name="phone"
                            label="Contact Phone"
                            :value="$user->phone"
                            placeholder="+1 234 567 890"
                        />

                        <div>
                            <x-inputs.text-area
                                name="bio"
                                label="About Company"
                                :value="$user->bio"
                                placeholder="Tell volunteers what your organization does, who you help, and what kind of events you create..."
                                rows="4"
                            />
                        </div>

                        <x-inputs.file
                            name="logo"
                            label="Change Logo"
                        />

                        <div class="flex flex-wrap gap-3 pt-2">
                            <button class="rounded-xl bg-emerald-600 px-5 py-2 text-white shadow transition hover:bg-amber-500">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2 text-white shadow transition hover:bg-emerald-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-100 bg-white p-6 md:p-8">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-emerald-600">Your Events</h2>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    </span>
                </div>

                @if (session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($events->count())
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($events as $event)
                            <div class="relative {{ $event->status === 'cancelled' ? 'opacity-60' : '' }}">
                                <x-event-card :event="$event" />
                                
                                <div class="absolute bottom-3 right-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold shadow-sm
                                        @if($event->status === 'published') bg-emerald-50 text-emerald-600
                                        @elseif($event->status === 'draft') bg-amber-50 text-amber-600
                                        @elseif($event->status === 'cancelled') bg-red-50 text-red-600
                                        @endif
                                    ">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>

                                {{-- Edit and applications --}}
                                <div class="absolute left-3 top-3 flex flex-col gap-2">

                                    <a href="{{ route('events.edit', $event) }}"
                                        class="rounded-xl bg-white px-3 py-2 text-xs font-semibold text-emerald-600 shadow hover:bg-emerald-50 border border-emerald-200">
                                        Edit
                                    </a>

                                    <a href="{{ route('events.applications', $event) }}"
                                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-amber-500">
                                        Applications ({{ $event->applications->count() }})
                                    </a>
                                </div>

                                <form action="{{ route('events.destroy', $event) }}"
                                    method="POST"
                                    class="absolute right-3 top-3"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="rounded-xl border border-amber-300 bg-white px-3 py-2 text-amber-500 shadow hover:bg-amber-50">
                                        Delete
                                    </button>
                                </form>
                                
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-emerald-200 bg-emerald-50/50 px-6 py-8 text-center text-gray-600">
                        You haven't created any events yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
