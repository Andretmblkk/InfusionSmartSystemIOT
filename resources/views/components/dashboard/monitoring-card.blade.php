@props([
    'status' => 'normal',
    'statusLabel' => null,
    'name',
    'room',
    'doctor',
    'initialVolume',
    'currentWeight',
    'timeRemaining',
    'progress',
    'progressTone' => 'green',
])

@php
    $accentClasses = [
        'normal' => 'border-b-[#007a3d]',
        'warning' => 'border-b-[#e9b400]',
        'critical' => 'border-b-[#c91e1e]',
    ][$status];

    $timeClasses = [
        'normal' => 'text-[#004891]',
        'warning' => 'text-[#a76500]',
        'critical' => 'text-[#d10f12]',
    ][$status];

    $labelClasses = [
        'normal' => 'text-[#007a3d]',
        'warning' => 'text-[#d78900]',
        'critical' => 'text-[#d10f12]',
    ][$status];
@endphp

<article class="flex min-h-[324px] flex-col rounded-[7px] border-b-4 {{ $accentClasses }} bg-white px-6 pb-6 pt-6 shadow-[0_10px_28px_rgba(39,82,120,0.04)]">
    <div class="flex items-start justify-between gap-4">
        <x-dashboard.monitoring-status-badge :status="$status" :label="$statusLabel" />

        <div class="text-right">
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#7d8794]">
                @if ($status === 'critical')
                    Kritis!
                @else
                    Estimasi Habis
                @endif
            </p>
            <p class="mt-[5px] text-[21px] font-extrabold leading-none tracking-[0.02em] {{ $timeClasses }}" data-live-countdown="{{ $timeRemaining }}">{{ $timeRemaining }}</p>
        </div>
    </div>

    <div class="mt-[48px]">
        <h3 class="text-[21px] font-extrabold leading-tight tracking-[-0.02em] text-[#1f252c]">{{ $name }}</h3>
        <p class="mt-[6px] flex flex-wrap items-center gap-x-[7px] text-[14px] font-medium leading-5 text-[#505968]">
            <span>{{ $room }}</span>
            <span class="size-[4px] rounded-full bg-[#b8c1cc]" aria-hidden="true"></span>
            <span>{{ $doctor }}</span>
        </p>
    </div>

    <dl class="mt-[25px] space-y-[18px]">
        <div class="flex items-center justify-between">
            <dt class="text-[10px] font-medium uppercase tracking-[0.14em] text-[#4f5865]">Volume Awal</dt>
            <dd class="text-[15px] font-extrabold text-[#1f252c]">{{ $initialVolume }}</dd>
        </div>
        <div class="flex items-center justify-between">
            <dt class="text-[10px] font-medium uppercase tracking-[0.14em] text-[#4f5865]">Berat Saat Ini</dt>
            <dd class="text-[15px] font-extrabold text-[#1f252c]">{{ $currentWeight }}</dd>
        </div>
    </dl>

    <div class="mt-auto pt-[34px]">
        <div class="mb-3 flex items-center justify-between">
            <p class="text-[12px] font-extrabold uppercase {{ $labelClasses }}">Sisa Cairan</p>
            <p class="text-[20px] font-extrabold leading-none tracking-[-0.01em] text-[#1f252c]">{{ $progress }}%</p>
        </div>
        <x-dashboard.monitoring-progress :value="$progress" :tone="$progressTone" />
    </div>
</article>
