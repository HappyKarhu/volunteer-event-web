@props(['name', 'label' => null, 'options' => []])

@php
$baseInput =
    'w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm
    focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
    transition';
@endphp

<div>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <select name="{{ $name }}" {{ $attributes->merge(['class' => $baseInput]) }}>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>