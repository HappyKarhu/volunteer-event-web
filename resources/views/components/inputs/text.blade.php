<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>

    <input 
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border rounded-lg']) }}
    >

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>