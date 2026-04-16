<x-layout>

<div x-data="{ tab: 'profile' }"
     class="mx-auto mt-10 w-full max-w-6xl space-y-8 px-5">

    {{-- Header --}}
    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-md">

        <div class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-amber-400 px-6 py-10 md:px-10">

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                {{-- Volunteer info --}}
                <div class="flex items-center gap-5">

                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="h-24 w-24 rounded-full border-4 border-white/70 bg-white object-cover shadow-lg md:h-28 md:w-28">

                    <div class="text-white">
                        <p class="mb-2 inline-flex rounded-full bg-white/20 px-3 py-1 text-xs uppercase tracking-[0.2em]">
                            Volunteer
                        </p>

                        <h1 class="text-3xl font-bold md:text-4xl">
                            {{ $user->name }}
                        </h1>

                        <p class="mt-2 max-w-2xl text-sm text-emerald-50">
                            Show what you care about, highlight your strengths, and keep your opportunities close by.
                        </p>
                    </div>

                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                    <div class="rounded-2xl bg-white/15 px-5 py-4 text-white">
                        <p class="text-xs uppercase text-emerald-50">Applied</p>
                        <p class="mt-2 text-3xl font-bold">{{ $applications->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-white/15 px-5 py-4 text-white">
                        <p class="text-xs uppercase text-emerald-50">Saved</p>
                        <p class="mt-2 text-3xl font-bold">{{ $savedEvents->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-white/15 px-5 py-4 text-white">
                        <p class="text-xs uppercase text-emerald-50">Profile</p>
                        <p class="mt-2 text-lg font-semibold">
                            {{ $user->bio && $user->skills ? 'Complete' : 'In Progress' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-3 p-6">

            <button @click="tab='applications'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition"
                    :class="tab==='applications' ? 'bg-emerald-600 text-white' : 'bg-white border text-gray-600'">
                Applications
            </button>

            <button @click="tab='history'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold"
                    :class="tab==='history' ? 'bg-emerald-600 text-white' : 'bg-white border text-gray-600'">
                History
            </button>

            <button @click="tab='saved'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition"
                    :class="tab==='saved' ? 'bg-emerald-600 text-white' : 'bg-white border text-gray-600'">
                Saved
            </button>

            <button @click="tab='profile'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition"
                    :class="tab==='profile' ? 'bg-emerald-600 text-white' : 'bg-white border text-gray-600'">
                Profile
            </button>
        </div>

        {{--  PROFILE  --}}
        <div x-show="tab==='profile'" x-cloak class="grid gap-6 p-6 md:grid-cols-3 md:p-8">

            {{-- Left side --}}
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
            {{-- Right side --}}
            <div class="md:col-span-2">

                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">

                    <h2 class="text-xl font-semibold text-emerald-600">
                        Edit Volunteer Profile
                    </h2>

                    @if (session('status') === 'profile-updated')
                        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            Profile updated successfully.
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('profile.update', ['tab' => 'profile']) }}"
                          enctype="multipart/form-data"
                          class="mt-4 space-y-4">

                        @csrf
                        @method('PUT')

                        <x-inputs.text name="name" label="Full Name" :value="$user->name" />
                        <x-inputs.text-area name="bio" label="Bio" :value="$user->bio" rows="4" />
                        <x-inputs.text-area name="skills" placeholder="Enter your skills, separated by commas" label="Skills" :value="$user->skills" rows="4" />
                        <x-inputs.file name="avatar" label="Change Avatar" />

                        <button class="rounded-xl bg-emerald-600 px-5 py-2 text-white shadow hover:bg-amber-500">
                            Save Changes
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button class="rounded-xl bg-amber-500 px-5 py-2 text-white shadow hover:bg-emerald-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- APPLICATIONS --}}
        <div x-show="tab==='applications'" x-cloak class="border-t border-gray-100 bg-white p-6 md:p-8">

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                @foreach($applications as $application)

                    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">

                        {{-- EVENT CARD --}}
                        <x-event-card
                            :event="$application->event"
                            :application="$application"
                            :status="$application->status"
                            :statusLabel="$application->status_label"
                        />
                    </div>
                @endforeach
            </div>

        </div>

        {{-- HISTORY --}}
        <div x-show="tab==='history'" x-cloak class="border-t border-gray-100 bg-white p-6 md:p-8">

        <div class="space-y-4">

            @foreach($applications as $application)

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

                    {{-- Header --}}
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $application->event->title }}
                            </p>

                            <p class="text-xs text-gray-500">
                                Applied on {{ $application->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        @php
                            $statusClass = match($application->status) {
                                'approved' => 'bg-emerald-500',
                                'rejected' => 'bg-red-500',
                                'pending' => 'bg-amber-500',
                                'cancelled' => 'bg-zinc-500',
                                'waitlisted' => 'bg-indigo-500',
                                default => 'bg-slate-400'
                            };
                        @endphp

                        <span class="text-white text-xs px-3 py-1 rounded-full {{ $statusClass }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>

                    {{-- Timeline --}}
                    <div class="relative border-l-2 border-gray-200 pl-6 space-y-5">

                        @forelse($application->statusHistory as $history)

                            <div class="relative pl-6">

                                {{-- content --}}
                                <div class="flex items-center justify-between">

                                    <div>
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 capitalize">
                                            {{ $history->status }}
                                        </span>

                                        <p class="text-xs text-gray-500">
                                            by {{ $history->user?->name ?? 'System' }}
                                        </p>
                                    </div>

                                    <span class="text-xs text-gray-400 whitespace-nowrap">
                                        {{ $history->created_at->format('M d, H:i') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="pl-6 relative">

                                <div class="absolute left-0 top-2 h-full w-px bg-gray-200"></div>

                                <p class="text-sm text-gray-500 italic">
                                    No updates yet — waiting for review
                                </p>

                            </div>
                        @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
            
        {{--  Saved Events --}}
        <div x-show="tab==='saved'" x-cloak class="border-t border-gray-100 bg-gray-50/60 p-6 md:p-8">

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                @foreach($savedEvents as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display:none !important; }
</style>

</x-layout>