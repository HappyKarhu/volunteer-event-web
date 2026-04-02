@props(['url' => '/', 'active' => true, 'icon' => null, 'mobile' => null])

@if($mobile)
<a href="{{$url}}" class="block px-4 py-2 hover:bg-emerald-500 {{$active ? 'text-yellow-500 font-bold' : ''}}">
    @if($icon)
    <i class="fa fa-{{$icon}}"></i>
    @endif
    {{$slot}}
</a>
@else
<a href="{{ $url }}" class="text-white hover:text-amber-500 py-2 {{ $active ? 'text-amber-600 font-bold' : '' }}">
    @if ($icon)
        <i class="fa fa-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</a>
@endif
