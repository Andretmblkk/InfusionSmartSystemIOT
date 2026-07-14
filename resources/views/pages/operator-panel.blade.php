@extends('layouts.dashboard')

@section('title', 'Panel Operator - VitalFlow')

@push('head')
    <meta http-equiv="refresh" content="5">
@endpush

@php
    $activeNav = 'operator';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Panel Operator';
    $topbarMode = 'patient-input';

    $conditionButtons = [
        ['value' => 'normal', 'label' => 'Normal', 'icon' => 'circle-check', 'class' => 'bg-[#e9f7ef] text-[#0d7a3d] border-[#cfe7d6]'],
        ['value' => 'offline', 'label' => 'Offline', 'icon' => 'cloud', 'class' => 'bg-[#eef2f6] text-[#5c6774] border-[#d8e0e8]'],
        ['value' => 'empty', 'label' => 'Habis', 'icon' => 'droplet', 'class' => 'bg-[#fdecee] text-[#c91e1e] border-[#f5c7cc]'],
    ];

    $summaryTotal = count($operatorCards ?? []);
    $summaryOverrides = collect($operatorCards ?? [])->where('overrideActive', true)->count();
    $summaryOnline = collect($operatorCards ?? [])->where('hardwareOnline', true)->count();
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[1080px] pb-10">
        <div class="pt-[4px]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">Panel Operator</h2>
                    <p class="mt-[7px] max-w-[720px] text-[15px] font-medium leading-6 text-[#4d5561]">
                        Kendalikan fallback demo per bed dari HP atau laptop. Saat alat belum terbaca stabil, operator bisa menjaga alur presentasi tetap jalan lalu kembali ke data alat saat node sudah pulih.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 lg:min-w-[360px]">
                    <div class="rounded-[10px] bg-white px-4 py-4 shadow-[0_10px_28px_rgba(39,82,120,0.05)]">
                        <div class="flex items-center gap-2 text-[#1152e8]">
                            <x-ui.icon name="monitor" class="size-5" />
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Bed Tersedia</p>
                        </div>
                        <p class="mt-3 text-[22px] font-extrabold text-[#1f252c]">{{ $summaryTotal }}</p>
                    </div>
                    <div class="rounded-[10px] bg-white px-4 py-4 shadow-[0_10px_28px_rgba(39,82,120,0.05)]">
                        <div class="flex items-center gap-2 text-[#a76500]">
                            <x-ui.icon name="shield" class="size-5" />
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Override Aktif</p>
                        </div>
                        <p class="mt-3 text-[22px] font-extrabold text-[#1f252c]">{{ $summaryOverrides }}</p>
                    </div>
                    <div class="rounded-[10px] bg-white px-4 py-4 shadow-[0_10px_28px_rgba(39,82,120,0.05)]">
                        <div class="flex items-center gap-2 text-[#0d7a3d]">
                            <x-ui.icon name="wifi" class="size-5" />
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Alat Online</p>
                        </div>
                        <p class="mt-3 text-[22px] font-extrabold text-[#1f252c]">{{ $summaryOnline }}</p>
                    </div>
                </div>
            </div>
        </div>

        <section class="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-2">
            @foreach ($operatorCards as $card)
                <article class="overflow-hidden rounded-[12px] border border-[#e6edf4] bg-white shadow-[0_14px_34px_rgba(39,82,120,0.06)]">
                    <div class="border-b border-[#edf2f7] bg-[#fbfdff] px-5 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-11 shrink-0 items-center justify-center rounded-[10px] bg-[#eaf2ff] text-[#1152e8]">
                                        <x-ui.icon name="monitor" class="size-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[19px] font-extrabold text-[#1f252c]">{{ $card['bedLabel'] }}</p>
                                        <p class="mt-1 text-[12px] font-semibold text-[#6a7480]">Node {{ $card['nodeId'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex shrink-0 flex-col items-end gap-2">
                                <span class="rounded-full px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.12em] {{ $card['overrideActive'] ? 'bg-[#fff4d6] text-[#a76500]' : 'bg-[#eaf2ff] text-[#1152e8]' }}">
                                    {{ $card['sourceLabel'] }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.12em] {{ $card['hardwareOnline'] ? 'bg-[#edf9f1] text-[#0d7a3d]' : 'bg-[#fff9ea] text-[#9a6a00]' }}">
                                    {{ $card['hardwareLabel'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 px-5 py-5">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1.2fr_0.8fr]">
                            <div class="rounded-[10px] bg-[#f5f8fc] px-4 py-4">
                                <div class="flex items-center gap-2 text-[#173b80]">
                                    <x-ui.icon name="id-card" class="size-4" />
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Monitoring Aktif</p>
                                </div>
                                <p class="mt-3 text-[16px] font-extrabold text-[#1f252c]">{{ $card['patientName'] ?? 'Belum ada pasien aktif' }}</p>
                                <p class="mt-1 text-[12px] font-medium leading-5 text-[#5f6c79]">{{ $card['roomName'] ?? 'Bed belum dipakai untuk monitoring' }}</p>
                            </div>

                            <div class="rounded-[10px] bg-[#f5f8fc] px-4 py-4">
                                <div class="flex items-center gap-2 text-[#173b80]">
                                    <x-ui.icon name="history" class="size-4" />
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Sinkronisasi</p>
                                </div>
                                <p class="mt-3 text-[13px] font-extrabold text-[#1f252c]">{{ $card['lastReadingLabel'] }}</p>
                                <p class="mt-1 text-[12px] font-medium leading-5 text-[#5f6c79]">
                                    @if ($card['realWeight'] !== null)
                                        {{ $card['realPercentage'] }}% | {{ $card['realWeight'] }} gram
                                    @else
                                        belum ada pembacaan
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-[10px] border border-[#edf2f7] bg-[#fbfcfd] px-4 py-4">
                                <div class="flex items-center gap-2 text-[#35506d]">
                                    <x-ui.icon name="monitor" class="size-4" />
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Status Web</p>
                                </div>
                                <p class="mt-3 text-[15px] font-extrabold text-[#1f252c]">{{ $card['displayStatus'] }}</p>
                                <p class="mt-1 text-[12px] font-semibold text-[#5f6c79]">{{ $card['displayPercentage'] }}% | {{ $card['displayWeight'] }} gram</p>
                            </div>

                            <div class="rounded-[10px] border border-[#edf2f7] bg-[#fbfcfd] px-4 py-4">
                                <div class="flex items-center gap-2 text-[#35506d]">
                                    <x-ui.icon name="flow" class="size-4" />
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.14em]">Override Saat Ini</p>
                                </div>
                                <p class="mt-3 text-[15px] font-extrabold text-[#1f252c]">{{ $card['conditionLabel'] }}</p>
                                <p class="mt-1 text-[12px] font-semibold text-[#5f6c79]">{{ $card['flowProfileLabel'] }}</p>
                            </div>
                        </div>

                        @if ($card['recoveredWhileOverride'])
                            <div class="rounded-[10px] border border-[#cfe7d6] bg-[#f1fbf4] px-4 py-3 text-[12px] font-semibold leading-5 text-[#0d7a3d]">
                                Alat asli sudah terhubung kembali. Kamu bisa lanjutkan demo dulu atau langsung tekan tombol kembali ke data alat.
                            </div>
                        @elseif (! $card['hardwareOnline'])
                            <div class="rounded-[10px] border border-[#f0dfb0] bg-[#fff9ea] px-4 py-3 text-[12px] font-semibold leading-5 text-[#9a6a00]">
                                Alat belum terhubung stabil. Override aman dipakai agar demo tetap mulus saat ujian.
                            </div>
                        @endif

                        @if ($card['canControl'])
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center gap-2 text-[#35506d]">
                                        <x-ui.icon name="shield" class="size-4" />
                                        <p class="text-[11px] font-extrabold uppercase tracking-[0.14em]">Kondisi</p>
                                    </div>
                                    <form method="POST" action="{{ route('operator-panel.condition', $card['bedNumber']) }}" class="mt-3 grid grid-cols-3 gap-2">
                                    @csrf
                                    @foreach ($conditionButtons as $button)
                                            <button type="submit" name="condition" value="{{ $button['value'] }}" class="inline-flex min-h-[48px] items-center justify-center gap-2 rounded-[10px] border px-3 text-[12px] font-extrabold shadow-[0_6px_12px_rgba(39,82,120,0.04)] {{ $button['class'] }} {{ $card['overrideActive'] && $card['condition'] === $button['value'] ? 'ring-2 ring-offset-1 ring-[#1152e8]' : '' }}">
                                                <x-ui.icon :name="$card['overrideActive'] && $card['condition'] === $button['value'] ? 'circle-check' : $button['icon']" class="size-4" />
                                                <span>{{ $button['label'] }}</span>
                                            </button>
                                    @endforeach
                                </form>
                            </div>

                                <div>
                                    <div class="flex items-center gap-2 text-[#35506d]">
                                        <x-ui.icon name="flow" class="size-4" />
                                        <p class="text-[11px] font-extrabold uppercase tracking-[0.14em]">Laju Aliran Simulasi</p>
                                    </div>
                                    <form method="POST" action="{{ route('operator-panel.flow', $card['bedNumber']) }}" class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                    @csrf
                                    @foreach ($flowProfiles as $profileKey => $profile)
                                        <button type="submit" name="flow_profile" value="{{ $profileKey }}" class="inline-flex min-h-[48px] items-center justify-center rounded-[10px] border px-3 text-[12px] font-extrabold shadow-[0_6px_12px_rgba(39,82,120,0.04)] {{ $card['flowProfile'] === $profileKey && $card['overrideActive'] ? 'border-[#1152e8] bg-[#1152e8] text-white' : 'border-[#dbe5f0] bg-[#eef4fb] text-[#1152e8]' }}">
                                                <span class="inline-flex items-center gap-2">
                                                    @if ($card['flowProfile'] === $profileKey && $card['overrideActive'])
                                                        <x-ui.icon name="circle-check" class="size-4" />
                                                    @endif
                                                    <span>{{ $profile['label'] }}</span>
                                                </span>
                                            </button>
                                        @endforeach
                                    </form>
                                </div>

                                <form method="POST" action="{{ route('operator-panel.release', $card['bedNumber']) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex min-h-[52px] w-full items-center justify-center gap-2 rounded-[10px] bg-[#005daa] px-4 text-[13px] font-extrabold text-white shadow-[0_12px_22px_rgba(0,83,164,0.20)]">
                                        <x-ui.icon name="wifi" class="size-4" />
                                        <span>Kembali ke Data Alat</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="rounded-[10px] border border-dashed border-[#d7dfe8] px-4 py-4 text-[12px] font-semibold leading-5 text-[#6d7886]">
                                Bed ini belum memiliki monitoring aktif, jadi override belum bisa dijalankan.
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>
    </div>
@endsection
