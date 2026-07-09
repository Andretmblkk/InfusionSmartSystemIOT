@props([
    'alerts' => [],
])

@php
    $styles = [
        'warning' => 'border-[#e9b400] bg-[#fff8d6] text-[#805200]',
        'critical' => 'border-[#c91e1e] bg-[#ffe4e1] text-[#9f1010]',
        'normal' => 'border-[#007a3d] bg-[#e5faee] text-[#087238]',
    ];
@endphp

@if (! empty($alerts))
    <div class="mb-5 space-y-3">
        @foreach ($alerts as $alert)
            <a href="{{ $alert['href'] }}" class="block rounded-[7px] border-l-4 px-4 py-3 text-[13px] font-semibold shadow-[0_10px_24px_rgba(39,82,120,0.05)] {{ $styles[$alert['status']] ?? $styles['warning'] }}">
                <span class="block text-[11px] font-extrabold uppercase tracking-[0.14em]">{{ $alert['label'] }}</span>
                <span class="mt-1 block leading-5">{{ $alert['message'] }}</span>
            </a>
        @endforeach
    </div>
@endif
