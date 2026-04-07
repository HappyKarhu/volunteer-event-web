@props(['disabled' => false, 'value' => ''])

<input 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm'
    ]) }}
    value="{{ $value }}"
>