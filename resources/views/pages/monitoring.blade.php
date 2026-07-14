@extends('layouts.dashboard')

@section('title', 'Monitoring Infus - VitalFlow')

@push('head')
    <meta http-equiv="refresh" content="5">
@endpush

@php
    $activeNav = 'monitoring';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Monitoring Infus Real-time';
    $topbarMode = 'patient-input';

    $patients = $monitoringPatients ?? [];
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[960px] pb-2">
        <x-dashboard.alert-stack :alerts="$alerts ?? []" />

        <div class="flex flex-col gap-5 pt-[4px] lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">Monitoring Infus</h2>
                <p class="mt-[7px] text-[16px] font-medium leading-6 text-[#4d5561]">Pantau aliran cairan pasien secara real-time dari seluruh bangsal.</p>
            </div>

            <div class="flex flex-wrap gap-4 lg:pb-[2px]">
                <x-dashboard.monitoring-summary-badge tone="green" :value="$stats['normal'] ?? 0" label="Normal" />
                <x-dashboard.monitoring-summary-badge tone="red" :value="$stats['critical'] ?? 0" label="Kritis" />
            </div>
        </div>

        <section class="mt-[39px] grid grid-cols-1 gap-x-6 gap-y-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($patients as $patient)
                <x-dashboard.monitoring-card
                    :status="$patient['status']"
                    :status-label="$patient['statusLabel']"
                    :name="$patient['name']"
                    :room="$patient['room']"
                    :doctor="$patient['doctor']"
                    :initial-volume="$patient['initialVolume']"
                    :current-weight="$patient['currentWeight']"
                    :time-remaining="$patient['timeRemaining']"
                    :progress="$patient['progress']"
                    :progress-tone="$patient['progressTone']"
                    :href="$patient['href'] ?? null"
                />
            @empty
                <div class="rounded-[7px] bg-white px-6 py-10 text-center text-[14px] font-semibold text-[#6b7480] shadow-[0_10px_28px_rgba(39,82,120,0.04)] md:col-span-2 xl:col-span-3">
                    Belum ada pasien aktif. Tambahkan pasien baru untuk mulai monitoring infus.
                </div>
            @endforelse
        </section>

        <x-dashboard.monitoring-footer />
    </div>
@endsection
