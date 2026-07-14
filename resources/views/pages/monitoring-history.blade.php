@extends('layouts.dashboard')

@section('title', 'Laporan Monitoring - VitalFlow')

@php
    $activeNav = 'history';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Laporan Monitoring';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[1080px] pb-10">
        <div class="flex flex-col gap-4 pt-[4px] sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">Laporan Monitoring</h2>
                <p class="mt-[7px] max-w-[760px] text-[15px] font-medium leading-6 text-[#4d5561]">
                    Setiap pasien ditampilkan dalam satu kartu laporan. Klik kartu untuk membuka rincian sesi infus, pergantian infus, dan penanggung jawabnya.
                </p>
            </div>

            <a href="{{ route('monitoring') }}" class="inline-flex h-11 items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">
                Kembali ke Monitoring
            </a>
        </div>

        <section class="mt-8 space-y-5">
            @forelse ($historyRows as $row)
                <a href="{{ $row['href'] }}" class="block rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)] transition hover:-translate-y-0.5 hover:shadow-[0_14px_30px_rgba(38,74,112,0.08)]">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-[22px] font-extrabold text-[#1f252c]">{{ $row['patient'] }}</h3>
                                <span class="rounded-[7px] bg-[#eef4fb] px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#1152e8]">{{ $row['medicalRecord'] }}</span>
                                <x-dashboard.status-badge :label="$row['status']" :tone="$row['tone']" />
                            </div>
                            <p class="mt-2 text-[14px] font-bold text-[#5b6572]">{{ $row['room'] }}</p>
                            <p class="mt-1 text-[13px] font-medium text-[#748191]">Dokter: {{ $row['doctor'] ?: '-' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 lg:min-w-[250px]">
                            <div class="rounded-[7px] bg-[#f5f8fb] px-4 py-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#7a8798]">Total Sesi</p>
                                <p class="mt-2 text-[22px] font-extrabold text-[#173b80]">{{ $row['infusionCount'] }}</p>
                            </div>
                            <div class="rounded-[7px] bg-[#f5f8fb] px-4 py-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#7a8798]">Ganti Infus</p>
                                <p class="mt-2 text-[22px] font-extrabold text-[#173b80]">{{ $row['replacementCount'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 border-t border-[#edf0f3] pt-5 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Bed Terakhir</p>
                            <p class="mt-2 text-[14px] font-extrabold text-[#1f252c]">{{ $row['bed'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Infus Terakhir</p>
                            <p class="mt-2 text-[14px] font-extrabold text-[#1f252c]">{{ $row['latestInfusion'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Perawat Terkait</p>
                            <p class="mt-2 text-[14px] font-extrabold leading-5 text-[#1f252c]">{{ $row['nurses'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Periode Monitoring</p>
                            <p class="mt-2 text-[14px] font-extrabold text-[#1f252c]">{{ $row['firstDate'] }}</p>
                            <p class="mt-1 text-[12px] font-medium text-[#748191]">Sesi terakhir: {{ $row['latestDate'] }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-[#edf0f3] pt-4">
                        <p class="text-[13px] font-medium text-[#66717f]">{{ $row['infusionNames'] }}</p>
                        <span class="text-[13px] font-extrabold text-[#005daa]">Buka Laporan Detail</span>
                    </div>
                </a>
            @empty
                <div class="rounded-[7px] bg-white p-8 text-center text-[14px] font-semibold text-[#6b7480] shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                    Belum ada laporan monitoring infus.
                </div>
            @endforelse
        </section>
    </div>
@endsection
