<x-layout>
    <x-slot name="title">Messages</x-slot>

    

    <div class="bg-white mx-auto p-8 rounded-2xl shadow-md w-full md:max-w-3xl border border-gray-100 space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Conversation with {{ $application->user->name }}
        </h1>

        {{-- Messages --}}
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5 space-y-4 max-h-96 overflow-y-auto">

    @forelse($application->messages as $msg)

        <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">

            <div class="max-w-xs px-4 py-3 rounded-2xl shadow-sm
                {{ $msg->sender_id === auth()->id()
                    ? 'bg-emerald-600 text-white'
                    : 'bg-amber-50 border border-gray-200 text-zinc-700' }}">

                <p class="text-xs opacity-70 mb-1">
                    {{ $msg->sender->name }}
                </p>

                <p class="text-sm leading-relaxed">
                    {{ $msg->message }}
                </p>

                <p class="text-[10px] mt-2 opacity-60 text-right">
                    {{ $msg->created_at->format('H:i') }}
                </p>
            </div>

        </div>

    @empty
        <p class="text-gray-400 text-sm text-center">
            No messages yet — start the conversation.
        </p>
    @endforelse

</div>

        {{-- Send message --}}
        <form method="POST"
            action="{{ route('applications.messages.store', $application) }}"
            class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm space-y-3">
            @csrf

            <x-inputs.text-area
                name="message"
                label="Message"
                placeholder="Type your message..."
                rows="3"
            />

            @error('message')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <div class="flex justify-end">
                <button class="px-5 py-2 bg-emerald-600 hover:bg-amber-500 text-white rounded-xl transition shadow">
                    Send Message
                </button>
            </div>
        </form>

    </div>
</x-layout>