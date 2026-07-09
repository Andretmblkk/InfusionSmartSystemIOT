@extends('layouts.dashboard')

@section('title', $patient->patient_name . ' - VitalFlow')

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
    $topbarTitle = 'Detail Pasien';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[960px] pb-10">
        <x-dashboard.alert-stack :alerts="$alerts ?? []" />

        <div class="flex flex-col gap-4 pt-[4px] sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">{{ $patient->patient_name }}</h2>
                <p class="mt-[7px] text-[16px] font-medium leading-6 text-[#4d5561]">{{ $detail['location'] }} - {{ $patient->doctor_name }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex h-11 items-center justify-center rounded-[7px] bg-[#e2e7ed] px-5 text-[14px] font-extrabold text-[#4f5964]">Kembali</a>
                <a href="{{ route('monitoring') }}" class="inline-flex h-11 items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">Monitoring</a>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_320px]">
            <section class="rounded-[7px] bg-white p-7 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="flex min-w-0 items-center gap-4">
                        <div class="flex size-16 shrink-0 items-center justify-center rounded-[14px] bg-[#e3edf8] text-[22px] font-extrabold text-[#005596]">
                            {{ $detail['initials'] }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[22px] font-extrabold leading-tight text-[#1f252c]">{{ $patient->patient_name }}</h3>
                            <p class="mt-1 text-[14px] font-medium text-[#606b78]">Perawat: {{ $patient->nurse_name }}</p>
                        </div>
                    </div>
                    <div class="sm:ml-auto">
                        <x-dashboard.status-badge :label="$detail['status']" :tone="$detail['statusTone']" />
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-[7px] bg-[#eef4f9] p-5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#728197]">Volume Awal</p>
                        <p class="mt-3 text-[24px] font-extrabold text-[#1f252c]">{{ $patient->initial_volume }} ml</p>
                    </div>
                    <div class="rounded-[7px] bg-[#eef4f9] p-5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#728197]">Berat Terbaca</p>
                        <p class="mt-3 text-[24px] font-extrabold text-[#1f252c]">{{ $detail['currentWeight'] }} gram</p>
                    </div>
                    <div class="rounded-[7px] bg-[#eef4f9] p-5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#728197]">Sisa Infus</p>
                        <p class="mt-3 text-[24px] font-extrabold text-[#005596]">{{ $detail['percentage'] }}%</p>
                    </div>
                    <div class="rounded-[7px] bg-[#eef4f9] p-5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#728197]">Estimasi Habis</p>
                        <p class="mt-3 text-[24px] font-extrabold text-[#005596]" data-live-countdown="{{ $detail['timeRemaining'] }}">{{ $detail['timeRemaining'] }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-[12px] font-extrabold uppercase text-[#007a3d]">Sisa Cairan</p>
                        <p class="text-[20px] font-extrabold text-[#1f252c]">{{ $detail['percentage'] }}%</p>
                    </div>
                    <x-dashboard.monitoring-progress :value="$detail['percentage']" :tone="$detail['progressTone']" />
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                    <h3 class="text-[16px] font-extrabold text-[#1f252c]">Administrasi</h3>
                    <dl class="mt-5 space-y-4">
                        <div>
                            <dt class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Ruangan</dt>
                            <dd class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $patient->room_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Kasur Monitoring</dt>
                            <dd class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $detail['bedLabel'] ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Dokter</dt>
                            <dd class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $patient->doctor_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Waktu Pemasangan</dt>
                            <dd class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $patient->installed_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                    <h3 class="text-[16px] font-extrabold text-[#1f252c]">Ganti Infus</h3>
                    <form method="POST" action="{{ route('patients.replace-infusion', $patient) }}" class="mt-5 space-y-4">
                        @csrf

                        <x-dashboard.form-field label="Volume Baru (ml)" name="replacement_volume" type="number" :value="old('replacement_volume', $patient->initial_volume)" suffix="ml" min="1" max="5000" required />
                        <x-dashboard.form-field label="Waktu Ganti" name="replaced_at" type="datetime-local" :value="old('replaced_at', now()->format('Y-m-d\TH:i'))" required />

                        <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">
                            Mulai Infus Baru
                        </button>
                    </form>
                </section>

                <section class="rounded-[7px] bg-[#005596] p-6 text-white shadow-[0_8px_24px_rgba(0,83,164,0.18)]">
                    <h3 class="text-[16px] font-extrabold">Catatan Integrasi IoT</h3>
                    <p class="mt-3 text-[13px] leading-6 text-white/90">Data alat masuk berdasarkan kasur aktif. {{ $detail['bedLabel'] ?? 'Kasur' }} menggunakan alamat Node {{ $detail['nodeId'] ?? '-' }}.</p>
                </section>
            </aside>
        </div>
    </div>
@endsection
