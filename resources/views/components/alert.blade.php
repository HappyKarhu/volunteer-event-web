@props(['type', 'message'])

@if(session()->has('success') || session()->has('error'))
<div class="p-4 mb-4 text-sm text-white {{ $type === 'success' ? 'bg-green-500' : 'bg-red-500' }} rounded">
    {{ $message }}
</div>
@endif