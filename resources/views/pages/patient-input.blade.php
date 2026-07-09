@extends('layouts.dashboard')

@section('title', 'Input Data Pasien - VitalFlow')

@php
    $activeNav = 'patient-input';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Input Data Pasien';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto grid w-full max-w-[960px] grid-cols-1 items-start gap-8 pb-10 lg:grid-cols-[628px_298px]">
        <section class="rounded-[7px] bg-white px-8 py-8 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
            <form method="POST" action="{{ route('patients.store') }}" data-bed-confirm-form data-bed-occupancies='@json($bedOccupancies ?? [])'>
                @csrf
                <input type="hidden" name="confirm_bed_transfer" value="{{ old('confirm_bed_transfer', 0) }}" data-bed-confirm-input>

                <x-dashboard.form-section-heading
                    icon="clipboard-user"
                    title="Identitas & Administrasi"
                    description="Lengkapi data administrasi pasien sebelum memulai monitoring."
                />

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                    <x-dashboard.form-field label="Nama Pasien" name="patient_name" :value="old('patient_name')" placeholder="Masukkan nama lengkap" required />
                    <x-dashboard.form-field label="Nama Ruangan" name="room_name" :value="old('room_name')" placeholder="Contoh: ICU Bed 04" required />
                    <div class="space-y-[9px]">
                        <label for="bed_number" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">
                            Kasur Monitoring
                        </label>

                        <div class="relative">
                            <select
                                id="bed_number"
                                name="bed_number"
                                required
                                class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] caret-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('bed_number') ring-2 ring-[#c91e1e]/35 @enderror"
                            >
                                <option value="">Pilih kasur</option>
                                @foreach (($bedOptions ?? []) as $bedNumber => $bed)
                                    <option value="{{ $bedNumber }}" @selected((int) old('bed_number') === (int) $bedNumber)>
                                        {{ $bed['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] font-extrabold text-[#7a8aa2]">v</span>
                        </div>

                        @error('bed_number')
                            <p id="bed_number_error" class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p>
                        @enderror
                    </div>
                    <x-dashboard.form-field label="Dokter Penanggung Jawab" name="doctor_name" :value="old('doctor_name')" placeholder="dr. Nama Lengkap, Sp.A" required />
                    <x-dashboard.form-field label="Perawat Penanggung Jawab" name="nurse_name" :value="old('nurse_name')" placeholder="Nama Perawat" required />
                </div>

                <div class="my-[26px] h-px bg-[#edf0f3]"></div>

                <x-dashboard.form-section-heading
                    icon="iv"
                    title="Detail Cairan & Pemasangan"
                    description="Atur volume dan waktu inisiasi infus secara presisi."
                    tone="green"
                />

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                    <x-dashboard.form-field label="Volume Infus Awal (ml)" name="initial_volume" type="number" :value="old('initial_volume', 500)" suffix="ml" min="1" max="5000" required />
                    <x-dashboard.form-field label="Waktu Pemasangan" name="installed_at" type="datetime-local" :value="old('installed_at', now()->format('Y-m-d\TH:i'))" required />
                </div>

                <div class="mt-8 flex min-h-[154px] items-center justify-between gap-5 rounded-[7px] bg-[#e7ecf2] px-7 py-6" data-infusion-countdown data-base-minutes="272">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-[#728197]">Estimasi Waktu Habis</p>
                        <div class="mt-3 flex items-baseline gap-2 text-[#005596]">
                            <span class="text-[30px] font-extrabold leading-none" data-countdown-hours>04</span>
                            <span class="text-[12px] font-extrabold uppercase text-[#7c8ba2]">Jam</span>
                            <span class="text-[30px] font-extrabold leading-none" data-countdown-minutes>32</span>
                            <span class="text-[12px] font-extrabold uppercase text-[#7c8ba2]">Menit</span>
                            <span class="text-[24px] font-extrabold leading-none" data-countdown-seconds>00</span>
                            <span class="text-[12px] font-extrabold uppercase text-[#7c8ba2]">Detik</span>
                        </div>
                    </div>

                    <div class="flex size-[86px] shrink-0 items-center justify-center rounded-full border-[6px] border-[#007a3d] text-[14px] font-extrabold text-[#1f252c]" data-countdown-percent>
                        75%
                    </div>
                </div>

                <div id="patient-input-actions" class="mt-14 flex items-center justify-end gap-12">
                    <a href="{{ route('dashboard') }}" class="text-[16px] font-extrabold text-[#1f252c]">Batal</a>
                    <button type="submit" class="inline-flex h-12 min-w-[300px] items-center justify-center gap-2 rounded-[5px] bg-[#005daa] px-6 text-[16px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.2)]">
                        <x-ui.icon name="play-circle" class="size-5" />
                        Simpan & Mulai Monitoring
                    </button>
                </div>
            </form>
        </section>

        <aside class="space-y-6">
            <x-dashboard.guide-card />
            <x-dashboard.device-status-card />

            <div class="h-[192px] overflow-hidden rounded-[7px] bg-[#d8dde3] grayscale">
                <img
                    src="{{ asset('images/vitalflow-digital-hub.png') }}"
                    alt="Perangkat monitoring infus"
                    class="h-full w-full object-cover object-right"
                >
            </div>
        </aside>
    </div>

    <script>
        (() => {
            const form = document.querySelector('[data-bed-confirm-form]');
            if (! form) return;

            const select = form.querySelector('[name="bed_number"]');
            const confirmInput = form.querySelector('[data-bed-confirm-input]');
            const occupiedBeds = JSON.parse(form.dataset.bedOccupancies || '{}');
            let confirmedBed = confirmInput.value === '1' ? select.value : null;

            select.addEventListener('change', () => {
                confirmedBed = null;
                confirmInput.value = '0';
            });

            form.addEventListener('submit', (event) => {
                const bedNumber = select.value;
                const occupancy = occupiedBeds[bedNumber];

                if (! occupancy) {
                    confirmInput.value = '0';
                    return;
                }

                if (confirmedBed === bedNumber && confirmInput.value === '1') {
                    return;
                }

                const bedLabel = occupancy.bed_label || `Kasur ${bedNumber}`;
                const patientName = occupancy.patient_name || 'pasien lain';
                const confirmed = window.confirm(`${bedLabel} sedang dipakai pasien ${patientName}. Yakin alihkan ke pasien baru?`);

                if (! confirmed) {
                    event.preventDefault();
                    confirmInput.value = '0';
                    return;
                }

                confirmedBed = bedNumber;
                confirmInput.value = '1';
            });
        })();
    </script>
@endsection
