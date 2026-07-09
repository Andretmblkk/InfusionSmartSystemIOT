@extends('layouts.dashboard')

@section('title', 'Riwayat Monitoring - VitalFlow')

@php
    $activeNav = 'history';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Riwayat Monitoring';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[960px] pb-10">
        <div class="flex flex-col gap-4 pt-[4px] sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">Riwayat Monitoring</h2>
                <p class="mt-[7px] text-[16px] font-medium leading-6 text-[#4d5561]">Ringkasan pembacaan terakhir untuk kesiapan integrasi data IoT.</p>
            </div>

            <a href="{{ route('monitoring') }}" class="inline-flex h-11 items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">
                Lihat Monitoring
            </a>
        </div>

        <section class="mt-8 overflow-hidden rounded-[7px] bg-white shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
            <div class="flex h-[68px] items-center border-b border-[#e8edf2] px-6 sm:px-8">
                <h3 class="text-[16px] font-extrabold text-[#1f252c]">Log Pembacaan Terakhir</h3>
                <span class="ml-auto rounded-full bg-[#eaf2ff] px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#1152e8]">Placeholder IoT</span>
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[760px] border-collapse">
                    <thead>
                        <tr class="h-[50px] bg-[#fbfcfd] text-left text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#a0a5aa]">
                            <th class="pl-8 pr-4">Waktu</th>
                            <th class="px-4">Pasien</th>
                            <th class="px-4">Lokasi</th>
                            <th class="px-4">Berat</th>
                            <th class="px-4">Sisa</th>
                            <th class="pl-4 pr-8">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historyRows as $row)
                            <tr class="border-b border-[#edf0f3] last:border-b-0">
                                <td class="py-5 pl-8 pr-4 text-[13px] font-semibold text-[#4f5964]">{{ $row['time'] }}</td>
                                <td class="px-4 py-5 text-[14px] font-extrabold text-[#1f252c]">{{ $row['patient'] }}</td>
                                <td class="px-4 py-5 text-[13px] font-semibold text-[#4f5964]">{{ $row['room'] }}</td>
                                <td class="px-4 py-5 text-[13px] font-semibold text-[#1f252c]">{{ $row['weight'] }}</td>
                                <td class="px-4 py-5 text-[13px] font-extrabold text-[#1f252c]">{{ $row['percentage'] }}</td>
                                <td class="py-5 pl-4 pr-8">
                                    <x-dashboard.status-badge :label="$row['status']" :tone="$row['tone']" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-10 text-center text-[14px] font-semibold text-[#6b7480]">
                                    Belum ada data monitoring. Data riwayat final akan mengikuti endpoint IoT dari web PHP native.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-4 p-5 md:hidden">
                @forelse ($historyRows as $row)
                    <article class="rounded-[7px] border border-[#edf0f3] bg-[#fbfcfd] p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[14px] font-extrabold text-[#1f252c]">{{ $row['patient'] }}</p>
                                <p class="mt-1 text-[12px] font-semibold text-[#6a7480]">{{ $row['room'] }} - {{ $row['time'] }}</p>
                            </div>
                            <x-dashboard.status-badge :label="$row['status']" :tone="$row['tone']" />
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-[6px] bg-white p-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#8b95a3]">Berat</p>
                                <p class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $row['weight'] }}</p>
                            </div>
                            <div class="rounded-[6px] bg-white p-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#8b95a3]">Sisa</p>
                                <p class="mt-1 text-[14px] font-extrabold text-[#005596]">{{ $row['percentage'] }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[7px] bg-[#fbfcfd] p-5 text-center text-[14px] font-semibold text-[#6b7480]">
                        Belum ada data monitoring. Data riwayat final akan mengikuti endpoint IoT dari web PHP native.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
