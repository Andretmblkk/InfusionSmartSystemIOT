@props([
    'label',
    'value',
    'unit',
    'tone' => 'blue',
])

@php
    $toneClass = [
        'blue' => 'border-l-[#005596] text-[#005596] bg-white',
        'green' => 'border-l-[#007a3d] text-[#00824a] bg-white',
        'red' => 'border-l-[#c91e1e] text-[#c91e1e] bg-[#ffd8d4]',
    ][$tone];
@endphp

<div class="h-[84px] rounded-[7px] border-l-[4px] {{ $toneClass }} px-4 py-[17px] shadow-[0_10px_24px_rgba(38,74,112,0.06)]">
    <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#969da5]">{{ $label }}</p>
    <div class="mt-[10px] flex items-baseline gap-[5px]">
        <p class="text-[25px] font-extrabold leading-none">{{ $value }}</p>
        <p class="text-[12px] font-medium text-[#969da5]">{{ $unit }}</p>
    </div>
</div>
