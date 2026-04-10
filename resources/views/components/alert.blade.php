@props([
    'type' => 'success',
    'message',
])

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-[90%] max-w-md"
>
    <div
        class="backdrop-blur-md bg-white/70 border shadow-lg rounded-xl p-4 text-sm flex items-start justify-between gap-3"
        :class="{
            'border-emerald-300 text-emerald-800': '{{ $type }}' === 'success',
            'border-amber-300 text-amber-800': '{{ $type }}' === 'warning',
            'border-red-300 text-red-800': '{{ $type }}' === 'error',
        }"
    >
        <div class="font-medium">
            {{ $message }}
        </div>

        <button
            @click="show = false"
            class="text-gray-500 hover:text-black"
        >
            ✕
        </button>
    </div>
</div>