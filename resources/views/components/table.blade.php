@props([
    'class' => '',
])

@php
    $classes = 'w-full caption-bottom text-sm ' . $class;
@endphp

<div class="overflow-x-auto">
    <table {{ $attributes->class($classes) }}>
        {{ $slot }}
    </table>
</div>
