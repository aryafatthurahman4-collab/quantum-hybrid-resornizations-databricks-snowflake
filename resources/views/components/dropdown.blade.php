@props([
    'align' => 'end',
])

@php
    $alignments = [
        'start' => 'left-0',
        'end' => 'right-0',
    ];

    $alignClass = $alignments[$align] ?? $alignments['end'];
@endphp

<div x-data="{ open: false }" class="relative">
    <div @click="open = !open">
        {{ $trigger }}
    </div>
    
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute {{ $alignClass }} mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
        {{ $slot }}
    </div>
</div>
