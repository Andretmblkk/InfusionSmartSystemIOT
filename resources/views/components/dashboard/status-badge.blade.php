@props([
    'label',
    'tone' => 'green',
])

@php
    $classes = [
        'green' => 'bg-[#64e889] text-[#007a3d]',
        'red' => 'bg-[#ffc6c1] text-[#b5121b]',
        'cyan' => 'bg-[#76e2ea] text-[#006071]',
    ][$tone];
@endphp

<span class="inline-flex h-5 items-center gap-[6px] rounded-full px-[10px] text-[12px] font-semibold {{ $classes }}">
    <span class="size-[6px] rounded-full bg-current"></span>
    {{ $label }}
</span>
