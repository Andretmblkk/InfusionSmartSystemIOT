@props([
    'tone' => 'green',
    'value',
    'label',
])

@php
    $classes = [
        'green' => 'bg-[#dce4ea] text-[#111820]',
        'red' => 'bg-[#dce4ea] text-[#111820]',
    ][$tone];

    $dotClasses = [
        'green' => 'bg-[#007a3d]',
        'red' => 'bg-[#c91e1e]',
    ][$tone];
@endphp

<div class="inline-flex h-8 items-center gap-2 rounded-[4px] px-4 text-[12px] font-extrabold uppercase {{ $classes }}">
    <span class="size-3 rounded-full {{ $dotClasses }}"></span>
    <span>{{ $value }} {{ $label }}</span>
</div>
