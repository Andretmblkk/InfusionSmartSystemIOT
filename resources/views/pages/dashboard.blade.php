@extends('layouts.dashboard')

@section('title', 'Beranda - VitalFlow')

@push('head')
    <meta http-equiv="refresh" content="5">
@endpush

@php
    $activeNav = 'dashboard';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Beranda Monitoring';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto flex max-w-[928px] flex-col">
        <x-dashboard.alert-stack :alerts="$alerts ?? []" />

        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div class="dashboard-heading pt-[18px]">
                <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.02em] text-[#1f252c]">Daftar Pasien Aktif</h2>
                <p class="mt-[5px] text-[16px] font-medium text-[#4d5561]">Ruangan VIP Dewasa — Lantai 3 Sayap Barat</p>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-0 lg:w-[452px]">
                <x-dashboard.stat-card label="Total Pasien" :value="$stats['total'] ?? 0" unit="Jiwa" />
                <x-dashboard.stat-card label="Infus Aktif" :value="$stats['active'] ?? 0" unit="Unit" tone="green" />
                <x-dashboard.stat-card label="Kritis" :value="str_pad((string) ($stats['critical'] ?? 0), 2, '0', STR_PAD_LEFT)" unit="Alert" tone="red" />
            </div>
        </div>

        <div class="mt-8">
            <x-dashboard.patient-table :patients="$dashboardPatients ?? []" :total="$totalPatients ?? 0" :paginator="$patientPaginator ?? null" />
        </div>

        <div class="mt-8">
            <x-dashboard.info-panels :activity-panel="$activityPanel ?? []" />
        </div>

        <a href="{{ route('patients.create') }}" class="fixed bottom-[32px] right-[38px] hidden size-14 items-center justify-center rounded-[15px] bg-[#005daa] text-white shadow-[0_16px_30px_rgba(0,83,164,0.28)] lg:flex" aria-label="Tambah pasien baru">
            <x-ui.icon name="plus" class="size-8" />
        </a>
    </div>
@endsection
