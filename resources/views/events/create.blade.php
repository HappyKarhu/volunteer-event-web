<x-layout>
    <x-slot name="title">Create a new event</x-slot>

    <div class="bg-white mx-auto p-8 rounded-2xl shadow-md w-full md:max-w-3xl border border-gray-100">
        <h1 class="text-4xl text-center font-bold text-gray-800 mb-2">Create a new event</h1>
        <p>Fill out the form below to create a new volunteer event.</p>

        @php
            $sectionStyle = "p-5 border border-gray-100 rounded-xl bg-gray-50 space-y-4";
            $labelStyle = "block text-sm font-medium text-gray-700 mb-1";           
        @endphp

        
        <form
            action="{{ route('events.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6 mt-6"
            x-data="{
                showErrors: true,
                isFree: {{ old('is_free', 0) ? 'true' : 'false' }},
                type: '{{ old('type', 'simple') }}',
                sections: {{ \Illuminate\Support\Js::from(old('sections', [['role_name' => '', 'description' => '', 'capacity' => '']])) }},
                addSection() {
                    this.sections.push({ role_name: '', description: '', capacity: '' });
                },
                removeSection(index) {
                    this.sections.splice(index, 1);
                }
            }"
            x-init="setTimeout(() => showErrors = false, 5000)"
        >
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
                        <x-inputs.date
                            name="start_date"
                            label="Start Date"
                        />
                        {{-- Start date error --}}
                        @error('start_date')
                            <p x-show="showErrors" 
                            x-transition 
                            class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <x-inputs.date
                            name="end_date"
                            label="End Date"
                        />
                        {{-- End date error --}}
                        @error('end_date')
                            <p x-show="showErrors" 
                            x-transition 
                            class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <x-inputs.text
                        name="location"
                        label="Location"
                        placeholder="Event location"
                        :value="old('location')" />
            </div>

            {{-- Pricing --}}
            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Pricing</h2>

                <input type="hidden" name="is_free" value="0">
                <label class="flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="is_free" value="1" x-model="isFree">
                    Free Event
                </label>

                <div x-show="!isFree" x-transition>
                    <x-inputs.text
                        name="price"
                        label="Price (€)"
                        type="number"
                        step="0.01"
                    />
                    {{-- Price error --}}
                    @error('price')
                        <p x-show="showErrors" 
                        x-transition 
                        class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Event Type --}}
            <div class="grid md:grid-cols-2 gap-4">

                {{-- Simple Event --}}
                <label 
                    @click="type = 'simple'"
                    :class="type === 'simple' ? 'border-emerald-600 bg-emerald-50' : 'border-gray-300'"
                    class="cursor-pointer border rounded-xl p-4 hover:border-emerald-500 transition block"
                >
                    <input 
                        type="radio" 
                        name="type" 
                        value="simple" 
                        class="hidden"
                        x-model="type"
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
                <label 
                    @click="type = 'sectioned'"
                    :class="type === 'sectioned' ? 'border-emerald-600 bg-emerald-50' : 'border-gray-300'"
                    class="cursor-pointer border rounded-xl p-4 hover:border-emerald-500 transition block"
                >
                    <input 
                        type="radio" 
                        name="type" 
                        value="sectioned" 
                        class="hidden"
                        x-model="type"
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


                <div x-show="type === 'simple'" x-transition>
                    <x-inputs.text
                        name="capacity"
                        label="Capacity"
                        type="number"
                        placeholder="Maximum number of participants."
                        :value="old('capacity')" />
                </div>
    
            </div>

            <div x-show="type === 'sectioned'" x-transition class="{{ $sectionStyle }}">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-emerald-600">Roles and Capacities</h2>
                        <p class="text-sm text-gray-500">Add the roles volunteers can be assigned to after approval.</p>
                    </div>
                    <button type="button" @click="addSection()" class="rounded-lg border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                        Add Role
                    </button>
                </div>

                @error('sections')
                    <p x-show="showErrors" x-transition class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <template x-for="(section, index) in sections" :key="index">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-4">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="font-semibold text-gray-800" x-text="section.role_name || `Role ${index + 1}`"></h3>
                            <button type="button" @click="removeSection(index)" class="text-sm font-medium text-red-500 hover:text-red-600">
                                Remove
                            </button>
                        </div>

                        <input type="hidden" :name="`sections[${index}][id]`" :value="section.id ?? ''">

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="{{ $labelStyle }}">Role Name</label>
                                <input type="text" :name="`sections[${index}][role_name]`" x-model="section.role_name" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-emerald-500" placeholder="Cleaner, Speaker, Setup team...">
                            </div>

                            <div>
                                <label class="{{ $labelStyle }}">Capacity</label>
                                <input type="number" min="1" :name="`sections[${index}][capacity]`" x-model="section.capacity" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-emerald-500" placeholder="How many volunteers?">
                            </div>
                        </div>

                        <div>
                            <label class="{{ $labelStyle }}">Role Description</label>
                            <textarea :name="`sections[${index}][description]`" x-model="section.description" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-emerald-500" placeholder="What will this role help with?"></textarea>
                        </div>
                    </div>
                </template>
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

                {{-- Status --}}
                <div>
                    <x-inputs.select
                        name="status"
                        label="Status"
                        :options="[
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'cancelled' => 'Cancelled'
                        ]"
                    />
                    
                    @error('status')
                        <p x-show="showErrors" 
                        x-transition 
                        class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Photo --}}

                <div>
                    <label class="{{ $labelStyle }}">Event Image</label>
                    <input type="file" name="photo" id="event-photo" class="w-full text-gray-700" @change="eventPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" x-data="{ eventPreview: null }">
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
