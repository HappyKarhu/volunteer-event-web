<x-layout>
    <div class="min-h-screen flex justify-center items-center 0bg-white hover:shadow-lg transition hover:bg-emerald-50 flex flex-col h-full">
        <div class="bg-white rounded-2xl shadow-md w-full sm:max-w-md md:max-w-lg p-8 md:p-10 border border-gray-100">
            
            <!-- Header inside card -->
            <h1 class="text-3xl md:text-4xl font-bold text-emerald-600 text-center mb-4">
                {{ $title ?? 'Welcome!' }}
            </h1>
            @isset($subtitle)
                <p class="text-gray-600 text-center mb-6">{{ $subtitle }}</p>
            @endisset

            {{ $slot }}
        </div>
    </div>
</x-layout>