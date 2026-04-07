@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => ''
])

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>

    <input 
        type="text"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm'
            ]) }}
    >

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>