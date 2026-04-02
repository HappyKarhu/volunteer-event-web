<x-layout>
    <x-slot name="title">Create a new event</x-slot>

    <div class="bg-white mx-auto p-8 rounded-2xl shadow-md w-full md:max-w-3xl border border-gray-100">
        <h1 class="text-4xl text-center font-bold text-gray-800 mb-2">Create a new event</h1>
        <p>Fill out the form below to create a new volunteer event.</p>

        @php
            $sectionStyle = "p-5 border border-gray-100 rounded-xl bg-gray-50 space-y-4";
            $inputStyle = "w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500";
            $labelStyle = "block text-sm font-medium text-gray-700 mb-1";           
        @endphp

        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 mt-6">
            @csrf

            {{-- Basic Info --}}
            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Basic Information</h2>

                <div>
                    <x-inputs.text
                        name="title"
                        label="Event Name"
                        placeholder="Event Title"
                        :value="old('title')" />
                </div>

                <div>
                    <x-inputs.text-area 
                        name="description" 
                        label="Event description"
                        placeholder="Effective volunteer event descriptions should be concise, engaging, and provide all necessary details..." 
                        rows="3" 
                        class="{{ $inputStyle }}"
                    >
                        {{ old('description') }}
                    </x-inputs.text-area>
                </div>
            </div>

            {{-- Event Details --}}
            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Event Details</h2>

                <div>
                    <x-inputs.text-area 
                        name="responsibilities" 
                        label="Responsibilities"
                        placeholder="List the main responsibilities for this event." 
                        rows="3" 
                        class="{{ $inputStyle }}"
                    >
                        {{ old('responsibilities') }}
                    </x-inputs.text-area>
                </div>

                <div>
                    <x-inputs.text
                        name="requirements"
                        label="Requirements"
                        placeholder="List any requirements for participants."
                        :value="old('requirements')" />
                </div>
                

                <div>
                    <x-inputs.text
                        name="bring_wear"
                        label="What to Bring/Wear"
                        placeholder="List what participants should bring or wear."
                        :value="old('bring_wear')" />
                </div>
            </div>

            {{-- Date & Location --}}
            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Date & Location</h2>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="{{ $labelStyle }}">Start Date</label>
                        <input type="date" name="start_date" class="{{ $inputStyle }}">
                    </div>
                    <div>
                        <label class="{{ $labelStyle }}">End Date</label>
                        <input type="date" name="end_date" class="{{ $inputStyle }}">
                    </div>
                </div>

                <div>
                    <x-inputs.text
                        name="location"
                        label="Location"
                        placeholder="Event location"
                        :value="old('location')" />
                </div>
    
            </div>

            {{-- Pricing --}}
            <div x-data="{ isFree: false }" class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Pricing</h2>

                <input type="hidden" name="is_free" value="0">
                <label class="flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="is_free" value="1" x-model="isFree">
                    Free Event
                </label>

                <div x-show="!isFree" x-transition>
                    <label class="{{ $labelStyle }}">Price (€)</label>
                    <input type="number" step="0.01" name="price" class="{{ $inputStyle }}">
                </div>
            </div>

            {{-- Event Type --}}
            <div class="grid md:grid-cols-2 gap-4">

                {{-- Simple Event --}}
                <label class="cursor-pointer border rounded-xl p-4 hover:border-emerald-500 transition block">
                    <input 
                        type="radio" 
                        name="type" 
                        value="simple" 
                        class="hidden"
                        {{ old('type') == 'simple' ? 'checked' : '' }}
                    >

                    <div class="flex items-start gap-3">
                        <div class="text-2xl">👥</div>
                        <div>
                            <p class="font-semibold text-gray-800">Simple Event</p>
                            <p class="text-sm text-gray-500">
                                One group, everyone joins together.
                            </p>
                        </div>
                    </div>
                </label>

                {{-- Sectioned Event --}}
                <label class="cursor-pointer border rounded-xl p-4 hover:border-emerald-500 transition block">
                    <input 
                        type="radio" 
                        name="type" 
                        value="sectioned" 
                        class="hidden"
                        {{ old('type') == 'sectioned' ? 'checked' : '' }}
                    >

                    <div class="flex items-start gap-3">
                        <div class="text-2xl">🧩</div>
                        <div>
                            <p class="font-semibold text-gray-800">Sectioned Event</p>
                            <p class="text-sm text-gray-500">
                                Multiple teams or roles.
                            </p>
                        </div>
                    </div>
                </label>

                <div>
                    <label class="{{ $labelStyle }}">Event Type</label>
                    <select name="type" class="{{ $inputStyle }}">
                        <option value="simple">Simple</option>
                        <option value="sectioned">Sectioned</option>
                    </select>
                </div>

                <div>
                    <x-inputs.text
                        name="capacity"
                        label="Capacity"
                        type="number"
                        placeholder="Maximum number of participants."
                        :value="old('capacity')" />
                </div>
    
            </div>

            {{-- Extras --}}
            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Additional Settings</h2>

                <div>
                    <x-inputs.text
                        name="tags"
                        label="Tags"
                        placeholder="e.g. volunteering, environment, cleanup"
                        :value="old('tags')" />
                    <p class="text-gray-500 text-sm mt-1">Separate tags with commas</p>
                </div>

                <div>
                    <label class="{{ $labelStyle }}">Status</label>
                    <select name="status" class="{{ $inputStyle }}">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="{{ $labelStyle }}">Event Image</label>
                    <input type="file" name="photo" class="w-full text-gray-700">
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-amber-500 text-white font-semibold py-3 rounded-lg shadow transition">
                    Create Event
                </button>
            </div>
        </form>
    </div>
</x-layout>
