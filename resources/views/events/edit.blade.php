<x-layout>
    <x-slot name="title">Edit event</x-slot>

    <div class="bg-white mx-auto p-8 rounded-2xl shadow-md w-full md:max-w-3xl border border-gray-100">
        <h1 class="text-4xl text-center font-bold text-gray-800 mb-2">Edit Event</h1>
        <p>Update your event details, photo, and settings below.</p>

        @php
            $sectionStyle = "p-5 border border-gray-100 rounded-xl bg-gray-50 space-y-4";
            $inputStyle = "w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500";
            $labelStyle = "block text-sm font-medium text-gray-700 mb-1";
            $isFree = old('is_free', $event->is_free) ? 1 : 0;
            $eventType = old('type', $event->type);
            $sections = old('sections', $event->sections->map(fn ($section) => [
                'id' => $section->id,
                'role_name' => $section->role_name,
                'description' => $section->description,
                'capacity' => $section->capacity,
            ])->values()->all());
        @endphp

        <form
            action="{{ route('events.update', $event) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6 mt-6"
            x-data="{
                type: '{{ $eventType }}',
                isFree: {{ $isFree ? 'true' : 'false' }},
                sections: {{ \Illuminate\Support\Js::from($sections ?: [['role_name' => '', 'description' => '', 'capacity' => '']]) }},
                addSection() {
                    this.sections.push({ role_name: '', description: '', capacity: '' });
                },
                removeSection(index) {
                    this.sections.splice(index, 1);
                }
            }"
        >
            @csrf
            @method('PUT')

            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Basic Information</h2>

                <x-inputs.text
                    name="title"
                    label="Event Name"
                    placeholder="Event Title"
                    :value="old('title', $event->title)"
                />

                <x-inputs.text-area
                    name="description"
                    label="Event Description"
                    placeholder="Describe the event clearly for volunteers."
                    rows="4"
                    :value="old('description', $event->description)"
                />
            </div>

            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Event Details</h2>

                <x-inputs.text-area
                    name="responsibilities"
                    label="Responsibilities"
                    placeholder="List the main responsibilities for this event."
                    rows="3"
                    :value="old('responsibilities', $event->responsibilities)"
                />

                <x-inputs.text
                    name="requirements"
                    label="Requirements"
                    placeholder="List any requirements for participants."
                    :value="old('requirements', $event->requirements)"
                />

                <x-inputs.text
                    name="bring_wear"
                    label="What to Bring/Wear"
                    placeholder="List what participants should bring or wear."
                    :value="old('bring_wear', $event->bring_wear)"
                />
            </div>

            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Date & Location</h2>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="{{ $labelStyle }}" for="start_date">Start Date</label>
                        <input
                            id="start_date"
                            type="date"
                            name="start_date"
                            class="{{ $inputStyle }} @error('start_date') border-red-300 ring-1 ring-red-200 @enderror"
                            value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}"
                        >
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="{{ $labelStyle }}" for="end_date">End Date</label>
                        <input
                            id="end_date"
                            type="date"
                            name="end_date"
                            class="{{ $inputStyle }} @error('end_date') border-red-300 ring-1 ring-red-200 @enderror"
                            value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}"
                        >
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <x-inputs.text
                    name="location"
                    label="Location"
                    placeholder="Event location"
                    :value="old('location', $event->location)"
                />
            </div>

            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Pricing</h2>

                <input type="hidden" name="is_free" value="0">

                <label class="flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="is_free" value="1" x-model="isFree">
                    Free Event
                </label>

                <div x-show="!isFree" x-transition>
                    <label class="{{ $labelStyle }}" for="price">Price (EUR)</label>
                    <input
                        id="price"
                        type="number"
                        step="0.01"
                        name="price"
                        class="{{ $inputStyle }} @error('price') border-red-300 ring-1 ring-red-200 @enderror"
                        value="{{ old('price', $event->price) }}"
                    >
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
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
            </div>

            <div class="{{ $sectionStyle }}">
                <h2 class="text-lg font-semibold text-emerald-600">Additional Settings</h2>

                <div x-show="type === 'simple'" x-transition>
                    <x-inputs.text
                        name="capacity"
                        label="Capacity"
                        type="number"
                        placeholder="Maximum number of participants."
                        :value="old('capacity', $event->capacity)"
                    />
                </div>

                <x-inputs.text
                    name="tags"
                    label="Tags"
                    placeholder="e.g. volunteering, environment, cleanup"
                    :value="old('tags', $event->tags)"
                />

                <div>
                    <label class="{{ $labelStyle }}" for="status">Status</label>
                    <select id="status" name="status" class="{{ $inputStyle }} @error('status') border-red-300 ring-1 ring-red-200 @enderror">
                        <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label class="{{ $labelStyle }}" for="photo">Event Image</label>

                    @if ($event->photo)
                        <img
                            src="{{ asset('storage/' . $event->photo) }}"
                            alt="{{ $event->title }}"
                            class="h-40 w-full rounded-xl object-cover border border-gray-200"
                        >
                    @endif

                    <input id="photo" type="file" name="photo" class="w-full text-gray-700">
                    @error('photo')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500">Upload a new image only if you want to replace the current one.</p>
                </div>
            </div>

            <div x-show="type === 'sectioned'" x-transition class="{{ $sectionStyle }}">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-emerald-600">Roles and Capacities</h2>
                        <p class="text-sm text-gray-500">Manage the sections volunteers can be assigned to.</p>
                    </div>
                    <button type="button" @click="addSection()" class="rounded-lg border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                        Add Role
                    </button>
                </div>

                @error('sections')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror

                <template x-for="(section, index) in sections" :key="section.id ?? index">
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
                                <input type="text" :name="`sections[${index}][role_name]`" x-model="section.role_name" class="{{ $inputStyle }}" placeholder="Cleaner, Speaker, Setup team...">
                            </div>

                            <div>
                                <label class="{{ $labelStyle }}">Capacity</label>
                                <input type="number" min="1" :name="`sections[${index}][capacity]`" x-model="section.capacity" class="{{ $inputStyle }}" placeholder="How many volunteers?">
                            </div>
                        </div>

                        <div>
                            <label class="{{ $labelStyle }}">Role Description</label>
                            <textarea :name="`sections[${index}][description]`" x-model="section.description" rows="3" class="{{ $inputStyle }}" placeholder="What will this role help with?"></textarea>
                        </div>
                    </div>
                </template>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Please fix the highlighted fields and try again.
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-amber-500 text-white font-semibold py-3 rounded-lg shadow transition">
                    Save Changes
                </button>

                <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layout>
