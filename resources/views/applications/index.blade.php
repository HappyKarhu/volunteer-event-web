<x-layout>
    <div class="max-w-5xl mx-auto mt-10 space-y-6">
        <h1 class="text-2xl font-bold">
            Applications for {{ $event->title }}
        </h1>

        @if($event->type === 'sectioned' && $event->sections->count())
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-emerald-700">Section Roles</h2>
                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @foreach($event->sections as $section)
                        <div class="rounded-xl border border-white bg-white p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $section->role_name }}</h3>
                                    @if($section->description)
                                        <p class="mt-1 text-sm text-gray-600">{{ $section->description }}</p>
                                    @endif
                                </div>
                                <span class="text-sm font-semibold text-emerald-700">
                                    {{ $section->volunteers->count() }}{{ $section->capacity ? ' / ' . $section->capacity : '' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach($applications as $application)
            <div class="p-5 border rounded-xl bg-white shadow-sm">
                <div class="flex justify-between items-start gap-6">
                    <div class="flex-1">
                        <h2 class="font-semibold">
                            <a href="{{ route('users.show', $application->user) }}"
                               class="text-emerald-600 hover:underline">
                                {{ $application->user->name }}
                            </a>
                        </h2>

                        @if($application->message)
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $application->message }}
                            </p>
                        @endif

                        @if($application->cv_path)
                            <a href="{{ asset('storage/' . $application->cv_path) }}"
                               target="_blank"
                               class="inline-block mt-2 text-sm text-emerald-600 underline">
                                View CV
                            </a>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('applications.show', $application) }}"
                               class="inline-block px-3 py-1 bg-emerald-600 text-white rounded">
                                Open Messages
                            </a>
                        </div>
                    </div>

                    <div class="text-sm shrink-0">
                        @if($application->isApproved())
                            <span class="text-green-600 font-semibold">Approved</span>
                        @elseif($application->isWaitlisted())
                            <span class="text-indigo-600 font-semibold">Waitlisted</span>
                        @elseif($application->isRejected())
                            <span class="text-red-600 font-semibold">Rejected</span>
                        @elseif($application->isCancelled())
                            <span class="text-gray-600 font-semibold">Cancelled</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Pending</span>
                        @endif
                    </div>
                </div>

                @if($application->isPending())
                    <div class="mt-4">
                        @if($event->type === 'simple')
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('applications.approve', $application) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('applications.reject', $application) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('applications.approve', $application) }}" class="space-y-2">
                                    @csrf

                                    <select name="event_section_id" class="w-full rounded-lg border border-gray-300 p-2">
                                        <option value="">Select section</option>
                                        @foreach($event->sections as $section)
                                            <option value="{{ $section->id }}">
                                                {{ $section->role_name }}
                                                @if(!is_null($section->capacity))
                                                    ({{ $section->volunteers->count() }}/{{ $section->capacity }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded">
                                        Approve and Assign
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('applications.reject', $application) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-layout>
