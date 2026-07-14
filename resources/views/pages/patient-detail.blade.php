@extends('layouts.dashboard')

@section('title', $patient->patient_name . ' - VitalFlow')

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
                            <dt class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Bed</dt>
                            <dd class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $detail['bedLabel'] ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#8b95a3]">Nama Infus Aktif</dt>
                            <dd class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $detail['infusionName'] ?? '-' }}</dd>
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

                        <x-dashboard.form-field label="Nama Pasien" name="readonly_patient_name" :value="$patient->patient_name" disabled />
                        <x-dashboard.form-field label="Ruangan" name="readonly_room_name" :value="$patient->room_name" disabled />
                        <x-dashboard.form-field label="Bed" name="readonly_bed" :value="$detail['bedLabel'] ?? '-'" disabled />
                        <x-dashboard.form-field label="Dokter Penanggung Jawab" name="readonly_doctor_name" :value="$patient->doctor_name" disabled />
                        <x-dashboard.form-field label="Waktu Ganti" name="readonly_replaced_at" type="datetime-local" :value="now()->format('Y-m-d\TH:i')" disabled />
                        <input type="hidden" name="replaced_at" value="{{ old('replaced_at', now()->format('Y-m-d\TH:i')) }}">

                        <div class="space-y-[9px]">
                            <label for="pengganti_data_infus_id" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">
                                Nama Infus Baru
                            </label>
                            <select id="pengganti_data_infus_id" name="pengganti_data_infus_id" required data-replacement-infusion-select class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('pengganti_data_infus_id') ring-2 ring-[#c91e1e]/35 @enderror">
                                <option value="">Pilih infus</option>
                                @foreach (($infusionProducts ?? []) as $infusionProduct)
                                    <option value="{{ $infusionProduct->id }}" data-volume="{{ $infusionProduct->volume_default_ml }}" @selected((int) old('pengganti_data_infus_id', $detail['monitoring']?->data_infus_id) === $infusionProduct->id)>
                                        {{ $infusionProduct->nama }} - {{ $infusionProduct->volume_default_ml }} ml
                                    </option>
                                @endforeach
                            </select>
                            @error('pengganti_data_infus_id') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                        </div>
                        <x-dashboard.form-field label="Berat Awal Infus Baru (ml)" name="replacement_volume" type="number" :value="old('replacement_volume', $patient->initial_volume)" suffix="ml" min="1" max="5000" required />
                        <div class="space-y-[9px]">
                            <label for="pengganti_data_perawat_id" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">
                                Perawat Penanggung Jawab
                            </label>
                            <select id="pengganti_data_perawat_id" name="pengganti_data_perawat_id" required class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('pengganti_data_perawat_id') ring-2 ring-[#c91e1e]/35 @enderror">
                                <option value="">Pilih perawat</option>
                                @foreach (($nurses ?? []) as $nurse)
                                    <option value="{{ $nurse->id }}" @selected((int) old('pengganti_data_perawat_id', $detail['monitoring']?->perawat_penanggung_jawab_id ?? $patient->data_perawat_id) === $nurse->id)>
                                        {{ $nurse->nama_lengkap }}{{ $nurse->unit ? ' - ' . $nurse->unit : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengganti_data_perawat_id') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">
                            Mulai Infus Baru
                        </button>
                    </form>
                </section>

                <section class="rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                    <h3 class="text-[16px] font-extrabold text-[#1f252c]">Selesai Monitoring</h3>
                    <form method="POST" action="{{ route('patients.finish-monitoring', $patient) }}" class="mt-5 space-y-4" onsubmit="return confirm('Yakin akhiri monitoring pasien ini? Bed akan siap dipakai pasien baru.');">
                        @csrf

                        <x-dashboard.form-field label="Waktu Selesai" name="finished_at" type="datetime-local" :value="old('finished_at', now()->format('Y-m-d\TH:i'))" required />

                        <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-[7px] bg-[#d71920] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(215,25,32,0.18)]">
                            Akhiri Monitoring
                        </button>
                    </form>
                </section>

                <section class="rounded-[7px] bg-[#005596] p-6 text-white shadow-[0_8px_24px_rgba(0,83,164,0.18)]">
                    <h3 class="text-[16px] font-extrabold">Catatan Integrasi IoT</h3>
                    <p class="mt-3 text-[13px] leading-6 text-white/90">Data alat masuk berdasarkan bed aktif. {{ $detail['bedLabel'] ?? 'Bed' }} menggunakan alamat Node {{ $detail['nodeId'] ?? '-' }}.</p>
                </section>
            </aside>
        </div>

        <section class="mt-6 rounded-[7px] bg-white p-7 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="text-[18px] font-extrabold text-[#1f252c]">Laporan Pergantian Infus</h3>
                    <p class="mt-1 text-[13px] font-semibold text-[#66717f]">Daftar formal kantong infus yang pernah dipakai pasien ini.</p>
                </div>
                <span class="rounded-full bg-[#eaf2ff] px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#1152e8]">
                    {{ count($detail['infusionSessions'] ?? []) }} sesi
                </span>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse (($detail['infusionSessions'] ?? []) as $session)
                    <article class="rounded-[7px] border border-[#edf0f3] bg-[#fbfcfd] p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[15px] font-extrabold text-[#1f252c]">{{ $session['infusionName'] }}</p>
                                <p class="mt-1 text-[12px] font-semibold text-[#6a7480]">{{ $session['startedAt'] }}</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.12em] text-[#4f5964]">{{ $session['status'] }}</span>
                        </div>

                        <dl class="mt-4 grid grid-cols-2 gap-3">
                            <div>
                                <dt class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#8b95a3]">Volume</dt>
                                <dd class="mt-1 text-[13px] font-extrabold text-[#1f252c]">{{ $session['volume'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#8b95a3]">Sisa Terakhir</dt>
                                <dd class="mt-1 text-[13px] font-extrabold text-[#005596]">{{ $session['latestPercentage'] }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#8b95a3]">Perawat Penanggung Jawab</dt>
                                <dd class="mt-1 text-[13px] font-extrabold text-[#1f252c]">{{ $session['nurse'] }}</dd>
                            </div>
                        </dl>
                    </article>
                @empty
                    <p class="rounded-[7px] bg-[#fbfcfd] p-5 text-center text-[14px] font-semibold text-[#6b7480]">Belum ada laporan pergantian infus.</p>
                @endforelse
            </div>
        </section>
    </div>

    <script>
        (() => {
            const select = document.querySelector('[data-replacement-infusion-select]');
            const volumeInput = document.querySelector('[name="replacement_volume"]');
            if (! select || ! volumeInput) return;

            select.addEventListener('change', () => {
                const selected = select.options[select.selectedIndex];
                const volume = selected?.dataset?.volume;
                if (volume) {
                    volumeInput.value = volume;
                }
            });
        })();
    </script>
@endsection
