@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button',
    'disabled' => false,
])

@php
    $variants = [
        'default' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-slate-100 text-slate-900 hover:bg-slate-200 focus:ring-slate-500',
        'destructive' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'outline' => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-500',
        'ghost' => 'text-slate-700 hover:bg-slate-100 focus:ring-slate-500',
        'link' => 'text-blue-600 underline-offset-4 hover:underline focus:ring-blue-500',
    ];

    $sizes = [
        'default' => 'h-10 px-4 py-2',
        'sm' => 'h-9 px-3',
        'lg' => 'h-11 px-8',
        'icon' => 'h-10 w-10',
    ];

    $classes = [
        'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
        'disabled:pointer-events-none disabled:opacity-50',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
    ];
@endphp

<button {{ $attributes->merge(['type' => $type, 'disabled' => $disabled])->class($classes) }}>
    {{ $slot }}
</button>
