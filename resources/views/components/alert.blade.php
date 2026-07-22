@props([
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'bg-white border-slate-200 text-slate-950',
        'destructive' => 'border-red-200 text-red-900 bg-red-50',
    ];

    $classes = [
        'relative w-full rounded-lg border p-4',
        $variants[$variant] ?? $variants['default'],
    ];
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
