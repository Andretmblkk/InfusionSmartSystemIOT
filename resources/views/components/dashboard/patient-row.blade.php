@props([
    'initials',
    'name',
    'meta',
    'room',
    'status',
    'statusTone',
    'currentWeight',
    'emptyWeight',
    'fullWeight',
    'weightTone' => 'green',
    'avatarTone' => 'blue',
    'href' => null,
    'timeRemaining' => null,
])

@php
    $avatar = [
        'blue' => 'bg-[#e3edf8] text-[#005596]',
        'green' => 'bg-[#dbf3e8] text-[#007a3d]',
        'teal' => 'bg-[#dceeee] text-[#006071]',
    ][$avatarTone];

    $usableWeight = max(1, $fullWeight - $emptyWeight);
    $percentage = (int) round((($currentWeight - $emptyWeight) / $usableWeight) * 100);
    $percentage = max(0, min(100, $percentage));
    $weightIcon = $weightTone === 'red' ? 'text-[#c91e1e]' : ($weightTone === 'cyan' ? 'text-[#006071]' : 'text-[#007a3d]');
@endphp

<tr class="border-b border-[#edf0f3] last:border-b-0">
    <td class="dashboard-patient-cell py-[21px] pl-8 pr-4">
        <div class="flex items-center gap-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-[12px] {{ $avatar }} text-[15px] font-extrabold">
                {{ $initials }}
            </div>
            <div>
                <p class="text-[14px] font-extrabold leading-5 text-[#1f252c]">{{ $name }}</p>
                <p class="text-[11px] leading-4 text-[#8b929a]">{{ $meta }}</p>
            </div>
        </div>
    </td>
    <td class="dashboard-patient-cell px-4 py-[21px]">
        <span class="inline-flex h-6 items-center rounded-[2px] bg-[#e1e6eb] px-[9px] text-[12px] font-extrabold text-[#4f5964]">{{ $room }}</span>
    </td>
    <td class="dashboard-patient-cell px-4 py-[21px]">
        <x-dashboard.status-badge :label="$status" :tone="$statusTone" />
    </td>
    <td class="dashboard-patient-cell px-4 py-[21px]">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-[15px] font-extrabold text-[#1f252c]">{{ $percentage }}</span>
                <span class="text-[11px] text-[#969da5]">%</span>
                <x-ui.icon :name="$weightTone === 'red' ? 'arrow-right' : ($weightTone === 'cyan' ? 'flow' : 'circle-check')" class="size-4 {{ $weightIcon }} {{ $weightTone === 'red' ? '-rotate-90' : '' }}" />
            </div>
            @if ($timeRemaining)
                <p class="text-[10px] font-extrabold tracking-[0.08em] {{ $weightTone === 'red' ? 'text-[#c91e1e]' : 'text-[#005596]' }}" data-live-countdown="{{ $timeRemaining }}">
                    {{ $timeRemaining }}
                </p>
            @endif
        </div>
    </td>
    <td class="dashboard-patient-cell px-4 py-[21px]">
        <p class="text-[14px] font-semibold leading-4 {{ $weightTone === 'red' ? 'text-[#c91e1e]' : 'text-[#1f252c]' }}">{{ $currentWeight }} gram</p>
        <x-dashboard.progress-bar :tone="$weightTone" :value="$percentage" />
    </td>
    <td class="dashboard-patient-cell py-[21px] pl-4 pr-7 text-right">
        <a href="{{ $href ?? '#' }}" class="inline-flex size-6 items-center justify-center text-[#a6abb1]" aria-label="Lihat detail pasien {{ $name }}">
            <x-ui.icon name="dots" class="size-5" />
        </a>
    </td>
</tr>
