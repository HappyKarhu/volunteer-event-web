@props(['disabled' => false, 'value' => '', 'type' => 'text'])

<input
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg 
                    focus:ring-0 focus:ring-emerald-500 focus:ring-offset-0
                    focus:border-emerald-500
                    focus:outline-none shadow-sm transition'
    ]) }}
    value="{{ old($attributes->get('name'), $value) }}"
>