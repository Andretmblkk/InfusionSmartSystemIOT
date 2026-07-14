@props([
    'activityPanel' => [],
])

@php
    $updatedLabel = $activityPanel['updatedLabel'] ?? 'Belum ada pembacaan terbaru';
    $items = $activityPanel['items'] ?? [];
    $toneClasses = [
        'blue' => 'text-[#005596]',
        'green' => 'text-[#007a3d]',
        'yellow' => 'text-[#a76500]',
        'red' => 'text-[#b40000]',
    ];
@endphp

<section class="dashboard-info-panel grid h-[177px] grid-cols-1 overflow-hidden rounded-[7px] bg-[#eef4f9] lg:grid-cols-[1.05fr_1fr]">
    <div class="px-6 py-7">
        <div class="flex items-center gap-3">
            <x-ui.icon name="info" class="size-5 text-[#005596]" />
            <h3 class="text-[16px] font-extrabold text-[#005596]">Informasi Protokol Infus</h3>
        </div>
        <p class="dashboard-info-panel-copy mt-5 max-w-[455px] text-[14px] leading-[1.55] text-[#5f6873]">
            Sistem membaca berat cairan infus dari perangkat IoT, lalu mengubahnya menjadi persentase sisa infus. Lakukan pengecekan fisik saat status masuk peringatan atau kritis, dan tekan tombol 'Ganti Infus' setelah kantong infus diganti.
        </p>
    </div>

    <div class="flex items-center justify-between gap-6 px-6 py-7">
        <div>
            <h3 class="text-[16px] font-extrabold text-[#1f252c]">Log Aktivitas Terakhir</h3>
            <p class="mt-2 text-[12px] font-medium uppercase tracking-[0.26em] text-[#9ba2aa]">{{ $updatedLabel }}</p>
        </div>
        <div class="dashboard-activity-badges space-y-3 text-right">
            @forelse ($items as $item)
                <span class="inline-flex h-7 items-center rounded-[4px] bg-white px-4 text-[10px] font-extrabold shadow-[0_4px_12px_rgba(38,74,112,0.04)] {{ $toneClasses[$item['tone'] ?? 'blue'] ?? $toneClasses['blue'] }}">
                    {{ $item['label'] }}
                </span>
            @empty
                <span class="inline-flex h-7 items-center rounded-[4px] bg-white px-4 text-[10px] font-extrabold text-[#6d7886] shadow-[0_4px_12px_rgba(38,74,112,0.04)]">
                    Belum ada aktivitas monitoring
                </span>
            @endforelse
        </div>
    </div>
</section>
