<x-layout>
<div class="max-w-5xl mx-auto mt-10 space-y-6">

    <h1 class="text-2xl font-bold">
        Applications for {{ $event->title }}
    </h1>

    @foreach($applications as $application)
        <div class="p-5 border rounded-xl bg-white shadow-sm">

            <div class="flex justify-between items-start">
                
                <div>
                    <h2 class="font-semibold">
                        <a href="{{ route('users.show', $application->user) }}"
                           class="text-emerald-600 hover:underline">
                            {{ $application->user->name }}
                        </a>
                    </h2>

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $application->message }}
                    </p>

                    @if($application->cv_path)
                        <a href="{{ asset('storage/' . $application->cv_path) }}"
                           target="_blank"
                           class="text-sm text-emerald-600 underline">
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

                <div class="text-sm">
                    @if($application->isApproved())
                        <span class="text-green-600 font-semibold">Approved</span>
                    @elseif($application->isRejected())
                        <span class="text-red-600 font-semibold">Rejected</span>
                    @else
                        <span class="text-yellow-600 font-semibold">Pending</span>
                    @endif
                </div>

            </div>

            @if($application->isPending())
                <div class="mt-4 flex gap-2">

                    <form method="POST" action="{{ route('applications.approve', $application) }}">
                        @csrf
                        <button class="px-3 py-1 bg-emerald-600 text-white rounded">
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('applications.reject', $application) }}">
                        @csrf
                        <button class="px-3 py-1 bg-red-600 text-white rounded">
                            Reject
                        </button>
                    </form>

                </div>
            @endif

        </div>
    @endforeach

</div>
</x-layout>