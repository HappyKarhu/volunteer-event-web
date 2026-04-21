@props([
    'url' => '/', 
    'bgClass' => 'bg-amber-500', 
    'hoverClass' => 'hover:bg-amber-600',
    'textClass' => 'text-white',
    'icon' => null,
    'block' => false,
])

<a href="{{ $url }}" class="{{ $bgClass }} {{ $hoverClass }} {{ $textClass }} px-4 py-2 rounded-xl
    hover:shadow-md transition duration-300 
    {{ $block ? 'block w-full text-left' : 'inline-flex items-center' }} items-center">
    @if ($icon)
        <i class="fa fa-{{ $icon }} mr-1"></i>
    @endif
    {{ $slot }}
</a>