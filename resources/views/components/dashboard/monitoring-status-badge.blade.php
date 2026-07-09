@props([
    'status' => 'normal',
    'label' => null,
])

@php
    $styles = [
        'normal' => 'bg-[#6ee895] text-[#087238]',
        'warning' => 'bg-[#fff3a9] text-[#9b6500]',
        'critical' => 'bg-[#c91e1e] text-white',
    ];

    $labels = [
        'normal' => 'Normal',
        'warning' => 'Peringatan',
        'critical' => 'Ganti Infus',
    ];
@endphp

<span class="inline-flex h-[23px] min-w-[74px] items-center justify-center rounded-full px-3 text-[10px] font-extrabold uppercase tracking-[0.16em] {{ $styles[$status] }}">
    {{ $label ?? $labels[$status] }}
</span>
