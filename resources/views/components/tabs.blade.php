@props([
    'defaultTab' => 'tab-1',
])

<div x-data="{ activeTab: '{{ $defaultTab }}' }" class="w-full">
    <div class="flex border-b border-slate-200">
        {{ $tabs }}
    </div>
    <div class="mt-4">
        {{ $content }}
    </div>
</div>
