@props([
    'class' => '',
])

@php
    $classes = 'rounded-lg border bg-white text-slate-950 shadow-sm ' . $class;
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
