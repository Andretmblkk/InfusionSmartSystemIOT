@extends('layouts.dashboard')

@section('title', 'Laporan Detail Monitoring - VitalFlow')

@php
    $activeNav = 'history';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Laporan Detail Monitoring';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[1080px] pb-10">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[12px] font-extrabold uppercase tracking-[0.16em] text-[#7a8798]">{{ $reportRow['medicalRecord'] }}</p>
                <h2 class="mt-2 text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">{{ $patient->patient_name }}</h2>
                <p class="mt-[7px] text-[15px] font-medium leading-6 text-[#4d5561]">{{ $reportRow['room'] }} | Dokter: {{ $reportRow['doctor'] ?: '-' }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('monitoring.history') }}" class="inline-flex h-11 items-center justify-center rounded-[7px] border border-[#d5dce5] px-5 text-[14px] font-extrabold text-[#1f252c]">
                    Kembali ke Laporan
                </a>
                <a href="{{ route('patients.show', $patient) }}" class="inline-flex h-11 items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">
                    Lihat Detail Monitoring
                </a>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Status Terakhir</p>
                <p class="mt-2 text-[24px] font-extrabold text-[#173b80]">{{ $reportRow['status'] }}</p>
            </div>
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Total Sesi</p>
                <p class="mt-2 text-[24px] font-extrabold text-[#173b80]">{{ $reportRow['infusionCount'] }}</p>
            </div>
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Pergantian Infus</p>
                <p class="mt-2 text-[24px] font-extrabold text-[#173b80]">{{ $reportRow['replacementCount'] }}</p>
            </div>
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Bed Aktif / Terakhir</p>
                <p class="mt-2 text-[24px] font-extrabold text-[#173b80]">{{ $reportRow['bed'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[340px_minmax(0,1fr)]">
            <section class="rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <h3 class="text-[18px] font-extrabold text-[#1f252c]">Ringkasan Pasien</h3>

                <dl class="mt-5 space-y-4 text-[13px]">
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">No. Rekam Medis</dt>
                        <dd class="mt-1 font-bold text-[#1f252c]">{{ $patient->registeredPatient?->nomor_rekam_medis ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Nama Lengkap</dt>
                        <dd class="mt-1 font-bold text-[#1f252c]">{{ $patient->registeredPatient?->nama_lengkap ?: $patient->patient_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Jenis Kelamin</dt>
                        <dd class="mt-1 font-bold text-[#1f252c]">
                            @if ($patient->registeredPatient?->jenis_kelamin === 'L')
                                Laki-laki
                            @elseif ($patient->registeredPatient?->jenis_kelamin === 'P')
                                Perempuan
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Tempat / Tanggal Lahir</dt>
                        <dd class="mt-1 font-bold text-[#1f252c]">{{ $patient->registeredPatient?->tempat_lahir ?: '-' }} / {{ $patient->registeredPatient?->tanggal_lahir?->format('d-m-Y') ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Jaminan</dt>
                        <dd class="mt-1 font-bold text-[#1f252c]">{{ $patient->registeredPatient?->jenis_jaminan ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Perawat Terkait</dt>
                        <dd class="mt-1 font-bold text-[#1f252c]">{{ $reportRow['nurses'] }}</dd>
                    </div>
                    <div>
                        <dt class="font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Infus yang Digunakan</dt>
                        <dd class="mt-1 font-bold leading-6 text-[#1f252c]">{{ $reportRow['infusionNames'] }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <div class="flex flex-col gap-2 border-b border-[#edf0f3] pb-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-[18px] font-extrabold text-[#1f252c]">Detail Laporan Sesi Infus</h3>
                        <p class="mt-1 text-[14px] font-medium text-[#66717f]">Setiap sesi menunjukkan jenis infus, volume, waktu mulai, waktu selesai, dan penanggung jawab.</p>
                    </div>
                    <span class="text-[12px] font-extrabold uppercase tracking-[0.14em] text-[#7b8794]">Periode: {{ $reportRow['firstDate'] }} s.d. {{ $reportRow['latestDate'] }}</span>
                </div>

                <div class="mt-6 overflow-hidden rounded-[7px] border border-[#e6ebf1]">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#e6ebf1] text-left">
                            <thead class="bg-[#f7f9fb]">
                                <tr>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Sesi</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Infus</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Volume</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Perawat PJ</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Mulai</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Selesai</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Status</th>
                                    <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Sisa Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#edf0f3] bg-white">
                                @forelse ($detail['infusionSessions'] as $session)
                                    <tr>
                                        <td class="px-4 py-3 text-[13px] font-extrabold text-[#173b80]">Sesi {{ $session['sequence'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-extrabold text-[#1f252c]">{{ $session['infusionName'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $session['volume'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $session['nurse'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $session['startedAt'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $session['endedAt'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $session['status'] }}</td>
                                        <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $session['latestPercentage'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-5 text-center text-[13px] font-bold text-[#66717f]">Belum ada data sesi infus untuk pasien ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
