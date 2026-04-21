@props(['calendar'])

@php
    $toneClasses = [
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'indigo' => 'bg-indigo-100 text-indigo-700',
        'red' => 'bg-red-100 text-red-700',
        'sky' => 'bg-sky-100 text-sky-700',
        'slate' => 'bg-slate-100 text-slate-700',
    ];
@endphp

<div class="rounded-3xl border border-gray-100 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-5 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Calendar</p>
            <h2 class="mt-2 text-2xl font-semibold text-gray-900">{{ $calendar['heading'] }}</h2>
        </div>

        <div class="rounded-2xl bg-gray-50 px-4 py-3 text-sm text-gray-600">
            <p class="font-semibold text-gray-900">{{ $calendar['monthLabel'] }}</p>
            <p>Events are grouped by day for this month.</p>
        </div>
    </div>

    <div class="grid gap-6 p-6 lg:grid-cols-[minmax(0,2fr)_minmax(18rem,1fr)]">
        <div>
            <div class="mb-3 grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">
                @foreach($calendar['daysOfWeek'] as $dayName)
                    <div>{{ $dayName }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-2">
                @foreach($calendar['days'] as $day)
                    <div class="min-h-[8.5rem] rounded-2xl border p-2.5 {{ $day['isCurrentMonth'] ? 'border-gray-100 bg-white' : 'border-transparent bg-gray-50/80 text-gray-300' }} {{ $day['isToday'] ? 'ring-2 ring-emerald-200' : '' }}">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold {{ $day['isToday'] ? 'bg-emerald-600 text-white' : 'text-gray-700' }}">
                                {{ $day['date']->day }}
                            </span>

                            @if($day['events']->isNotEmpty())
                                <span class="text-[11px] font-medium text-gray-400">
                                    {{ $day['events']->count() }} {{ Str::plural('event', $day['events']->count()) }}
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2">
                            @foreach($day['events']->take(3) as $event)
                                @php
                                    $context = $event->calendar_context ?? ['label' => ucfirst($event->status ?? 'Planned'), 'tone' => 'slate'];
                                    $toneClass = $toneClasses[$context['tone']] ?? $toneClasses['slate'];
                                @endphp

                                <a href="{{ route('events.show', $event) }}"
                                   class="block rounded-xl border border-gray-100 bg-gray-50 px-2 py-2 transition hover:border-emerald-200 hover:bg-emerald-50">
                                    <p class="truncate text-xs font-semibold text-gray-900">{{ $event->title }}</p>
                                    <p class="mt-1 text-[11px] text-gray-500">
                                        {{ $event->start_date->format('M d') }}{{ $event->start_date->isSameDay($event->end_date) ? '' : ' - ' . $event->end_date->format('M d') }}
                                    </p>
                                    <span class="mt-2 inline-flex rounded-full px-2 py-1 text-[10px] font-semibold {{ $toneClass }}">
                                        {{ $context['label'] }}
                                    </span>
                                </a>
                            @endforeach

                            @if($day['events']->count() > 3)
                                <p class="px-1 text-[11px] font-medium text-gray-400">
                                    +{{ $day['events']->count() - 3 }} more
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming</h3>
                <span class="text-sm text-gray-500">{{ $calendar['upcomingEvents']->count() }} scheduled</span>
            </div>

            <div class="space-y-3">
                @forelse($calendar['upcomingEvents'] as $event)
                    @php
                        $context = $event->calendar_context ?? ['label' => ucfirst($event->status ?? 'Planned'), 'tone' => 'slate'];
                        $toneClass = $toneClasses[$context['tone']] ?? $toneClasses['slate'];
                    @endphp

                    <a href="{{ route('events.show', $event) }}"
                       class="block rounded-2xl border border-white bg-white px-4 py-3 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-100">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $event->start_date->format('M d, Y') }}{{ $event->location ? ' | ' . $event->location : '' }}</p>
                            </div>

                            <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-semibold {{ $toneClass }}">
                                {{ $context['label'] }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-white px-4 py-6 text-center text-sm text-gray-500">
                        No upcoming events in your calendar yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

