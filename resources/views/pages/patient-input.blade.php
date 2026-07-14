@extends('layouts.dashboard')

@section('title', 'Input Monitoring - VitalFlow')

@php
    $activeNav = 'patient-input';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Input Monitoring';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto grid w-full max-w-[1080px] grid-cols-1 items-start gap-8 pb-10 lg:grid-cols-[minmax(0,1fr)_300px]">
        <section class="rounded-[7px] bg-white px-8 py-8 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
            <div class="mb-7 rounded-[7px] bg-[#eef4fb] px-5 py-4 text-[13px] font-medium leading-6 text-[#36506d]">
                Halaman ini dipakai untuk membuat sesi monitoring baru. Jika identitas pasien belum tersedia, tambahkan dahulu melalui
                <a href="{{ route('master-data.index') }}" class="font-extrabold text-[#005daa] underline">Input Data Pasien</a>.
            </div>

            @if (($registeredPatients ?? collect())->isEmpty() || ($doctors ?? collect())->isEmpty() || ($nurses ?? collect())->isEmpty() || ($infusionProducts ?? collect())->isEmpty())
                <div class="mb-7 rounded-[7px] bg-[#fff5d6] px-5 py-4 text-[13px] font-bold leading-6 text-[#7a5200]">
                    Lengkapi data pasien, dokter, perawat, dan infus terlebih dahulu agar monitoring dapat dibuat dengan lengkap.
                </div>
            @endif

            <form method="POST" action="{{ route('patients.store') }}" data-bed-confirm-form data-bed-occupancies='@json($bedOccupancies ?? [])'>
                @csrf
                <input type="hidden" name="confirm_bed_transfer" value="{{ old('confirm_bed_transfer', 0) }}" data-bed-confirm-input>

                <x-dashboard.form-section-heading
                    icon="clipboard-user"
                    title="Identitas Pasien dan Penempatan"
                    description="Pilih pasien terdaftar, tentukan lokasi bed, lalu tetapkan tenaga medis yang bertanggung jawab."
                />

                <div class="mt-8 space-y-6">
                    <div class="space-y-[9px]">
                        <label for="data_pasien_id" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">
                            Pasien Terdaftar
                        </label>

                        <select
                            id="data_pasien_id"
                            name="data_pasien_id"
                            required
                            data-patient-select
                            class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[15px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('data_pasien_id') ring-2 ring-[#c91e1e]/35 @enderror"
                        >
                            <option value="">Pilih pasien terdaftar</option>
                            @foreach (($registeredPatients ?? []) as $registeredPatient)
                                <option
                                    value="{{ $registeredPatient->id }}"
                                    data-rm="{{ $registeredPatient->nomor_rekam_medis }}"
                                    data-nama="{{ $registeredPatient->nama_lengkap }}"
                                    data-jk="{{ $registeredPatient->jenis_kelamin === 'L' ? 'Laki-laki' : ($registeredPatient->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}"
                                    data-lahir="{{ $registeredPatient->tempat_lahir ?: '-' }} / {{ $registeredPatient->tanggal_lahir?->format('d-m-Y') ?: '-' }}"
                                    data-golongan="{{ $registeredPatient->golongan_darah ?: '-' }}"
                                    data-jaminan="{{ $registeredPatient->jenis_jaminan ?: '-' }}"
                                    data-penanggungjawab="{{ $registeredPatient->nama_penanggung_jawab ?: '-' }}"
                                    data-alergi="{{ $registeredPatient->alergi ?: '-' }}"
                                    @selected((int) old('data_pasien_id') === $registeredPatient->id)
                                >
                                    {{ $registeredPatient->nomor_rekam_medis }} | {{ $registeredPatient->nama_lengkap }} | {{ $registeredPatient->tanggal_lahir?->format('d-m-Y') ?: '-' }} | {{ $registeredPatient->jenis_jaminan ?: 'Tanpa jaminan' }}
                                </option>
                            @endforeach
                        </select>

                        @error('data_pasien_id')
                            <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-[7px] border border-[#e2e8f0] bg-[#fbfcfe] p-5" data-patient-preview>
                        <div class="flex items-center justify-between gap-4 border-b border-[#edf0f3] pb-4">
                            <div>
                                <p class="text-[12px] font-extrabold uppercase tracking-[0.14em] text-[#7a8798]">Ringkasan Pasien</p>
                                <h3 class="mt-2 text-[20px] font-extrabold text-[#1f252c]" data-patient-preview-name>Belum ada pasien dipilih</h3>
                            </div>
                            <span class="rounded-[7px] bg-[#eaf2ff] px-3 py-2 text-[12px] font-extrabold text-[#1152e8]" data-patient-preview-rm>No. RM -</span>
                        </div>

                        <dl class="mt-4 grid grid-cols-1 gap-4 text-[13px] sm:grid-cols-2">
                            <div>
                                <dt class="font-extrabold uppercase tracking-[0.12em] text-[#7a8798]">Jenis Kelamin</dt>
                                <dd class="mt-1 font-bold text-[#1f252c]" data-patient-preview-jk>-</dd>
                            </div>
                            <div>
                                <dt class="font-extrabold uppercase tracking-[0.12em] text-[#7a8798]">Tempat / Tanggal Lahir</dt>
                                <dd class="mt-1 font-bold text-[#1f252c]" data-patient-preview-lahir>-</dd>
                            </div>
                            <div>
                                <dt class="font-extrabold uppercase tracking-[0.12em] text-[#7a8798]">Golongan Darah</dt>
                                <dd class="mt-1 font-bold text-[#1f252c]" data-patient-preview-golongan>-</dd>
                            </div>
                            <div>
                                <dt class="font-extrabold uppercase tracking-[0.12em] text-[#7a8798]">Jaminan</dt>
                                <dd class="mt-1 font-bold text-[#1f252c]" data-patient-preview-jaminan>-</dd>
                            </div>
                            <div>
                                <dt class="font-extrabold uppercase tracking-[0.12em] text-[#7a8798]">Penanggung Jawab</dt>
                                <dd class="mt-1 font-bold text-[#1f252c]" data-patient-preview-penanggungjawab>-</dd>
                            </div>
                            <div>
                                <dt class="font-extrabold uppercase tracking-[0.12em] text-[#7a8798]">Alergi</dt>
                                <dd class="mt-1 font-bold text-[#1f252c]" data-patient-preview-alergi>-</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                    <div class="space-y-[9px]">
                        <label for="room_name" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Ruangan</label>
                        <select id="room_name" name="room_name" required class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('room_name') ring-2 ring-[#c91e1e]/35 @enderror">
                            <option value="">Pilih ruangan</option>
                            @foreach (($roomOptions ?? []) as $roomOption)
                                <option value="{{ $roomOption }}" @selected(old('room_name') === $roomOption)>{{ $roomOption }}</option>
                            @endforeach
                        </select>
                        @error('room_name') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-[9px]">
                        <label for="bed_number" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Bed</label>

                        <div class="relative">
                            <select
                                id="bed_number"
                                name="bed_number"
                                required
                                class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] caret-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('bed_number') ring-2 ring-[#c91e1e]/35 @enderror"
                            >
                                <option value="">Pilih bed</option>
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

                    <div class="space-y-[9px]">
                        <label for="data_dokter_id" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Dokter Penanggung Jawab</label>
                        <select id="data_dokter_id" name="data_dokter_id" required class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('data_dokter_id') ring-2 ring-[#c91e1e]/35 @enderror">
                            <option value="">Pilih dokter</option>
                            @foreach (($doctors ?? []) as $doctor)
                                <option value="{{ $doctor->id }}" @selected((int) old('data_dokter_id') === $doctor->id)>
                                    {{ $doctor->nama_lengkap }}{{ $doctor->spesialis ? ' | ' . $doctor->spesialis : '' }}{{ $doctor->unit ? ' | ' . $doctor->unit : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('data_dokter_id') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-[9px]">
                        <label for="data_perawat_id" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Perawat Penanggung Jawab</label>
                        <select id="data_perawat_id" name="data_perawat_id" required class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('data_perawat_id') ring-2 ring-[#c91e1e]/35 @enderror">
                            <option value="">Pilih perawat</option>
                            @foreach (($nurses ?? []) as $nurse)
                                <option value="{{ $nurse->id }}" @selected((int) old('data_perawat_id') === $nurse->id)>
                                    {{ $nurse->nama_lengkap }}{{ $nurse->unit ? ' | ' . $nurse->unit : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('data_perawat_id') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="my-[26px] h-px bg-[#edf0f3]"></div>

                <x-dashboard.form-section-heading
                    icon="iv"
                    title="Aktivasi Monitoring Infus"
                    description="Pilih jenis infus, tetapkan volume awal, lalu tentukan waktu mulai monitoring."
                    tone="green"
                />

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                    <div class="space-y-[9px]">
                        <label for="data_infus_id" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Nama Infus</label>
                        <select id="data_infus_id" name="data_infus_id" required data-infusion-product-select class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 pr-10 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 @error('data_infus_id') ring-2 ring-[#c91e1e]/35 @enderror">
                            <option value="">Pilih infus</option>
                            @foreach (($infusionProducts ?? []) as $infusionProduct)
                                <option value="{{ $infusionProduct->id }}" data-volume="{{ $infusionProduct->volume_default_ml }}" @selected((int) old('data_infus_id') === $infusionProduct->id)>
                                    {{ $infusionProduct->nama }} | {{ $infusionProduct->volume_default_ml }} ml{{ $infusionProduct->kategori ? ' | ' . $infusionProduct->kategori : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('data_infus_id') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <x-dashboard.form-field label="Volume Infus Awal (ml)" name="initial_volume" type="number" :value="old('initial_volume', 500)" suffix="ml" min="1" max="5000" required />
                    <x-dashboard.form-field label="Waktu Mulai Monitoring" name="installed_at" type="datetime-local" :value="old('installed_at', now()->format('Y-m-d\TH:i'))" required />
                </div>

                <div class="mt-8 rounded-[7px] bg-[#f6f9fc] px-6 py-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Protokol Bed</p>
                            <p class="mt-2 text-[13px] font-bold leading-6 text-[#1f252c]">Satu bed mewakili satu node. Ketika bed yang sama dipilih ulang, sistem akan meminta konfirmasi pengalihan monitoring.</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Sumber Identitas</p>
                            <p class="mt-2 text-[13px] font-bold leading-6 text-[#1f252c]">Nama pasien tidak diketik manual di sini. Semua identitas diambil dari data pasien yang sudah terdaftar agar formal dan konsisten.</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Penggantian Infus</p>
                            <p class="mt-2 text-[13px] font-bold leading-6 text-[#1f252c]">Setelah monitoring aktif, pergantian infus dilakukan dari detail pasien dan otomatis tercatat di laporan.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex items-center justify-end gap-8">
                    <a href="{{ route('dashboard') }}" class="text-[16px] font-extrabold text-[#1f252c]">Batal</a>
                    <button type="submit" class="inline-flex h-12 min-w-[320px] items-center justify-center gap-2 rounded-[5px] bg-[#005daa] px-6 text-[16px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.2)]">
                        <x-ui.icon name="play-circle" class="size-5" />
                        Simpan dan Aktifkan Monitoring
                    </button>
                </div>
            </form>
        </section>

        <aside class="space-y-6">
            <x-dashboard.guide-card />
            <x-dashboard.device-status-card />

            <div class="rounded-[7px] bg-white p-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Catatan Operasional</p>
                <ul class="mt-4 space-y-3 text-[13px] font-bold leading-6 text-[#1f252c]">
                    <li>Pastikan pasien sudah terdaftar di Input Data Pasien sebelum monitoring dibuat.</li>
                    <li>Pilih bed sesuai alat yang terpasang tetap pada lokasi tersebut.</li>
                    <li>Jika bed sedang aktif dipakai pasien lain, lakukan pengalihan hanya setelah dipastikan monitoring sebelumnya selesai.</li>
                </ul>
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

                const bedLabel = occupancy.bed_label || `Bed ${bedNumber}`;
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

        (() => {
            const select = document.querySelector('[data-infusion-product-select]');
            const volumeInput = document.querySelector('[name="initial_volume"]');
            if (! select || ! volumeInput) return;

            select.addEventListener('change', () => {
                const selected = select.options[select.selectedIndex];
                const volume = selected?.dataset?.volume;
                if (volume) {
                    volumeInput.value = volume;
                }
            });
        })();

        (() => {
            const select = document.querySelector('[data-patient-select]');
            const preview = document.querySelector('[data-patient-preview]');
            if (! select || ! preview) return;

            const fields = {
                name: preview.querySelector('[data-patient-preview-name]'),
                rm: preview.querySelector('[data-patient-preview-rm]'),
                jk: preview.querySelector('[data-patient-preview-jk]'),
                lahir: preview.querySelector('[data-patient-preview-lahir]'),
                golongan: preview.querySelector('[data-patient-preview-golongan]'),
                jaminan: preview.querySelector('[data-patient-preview-jaminan]'),
                penanggungjawab: preview.querySelector('[data-patient-preview-penanggungjawab]'),
                alergi: preview.querySelector('[data-patient-preview-alergi]'),
            };

            const setEmpty = () => {
                fields.name.textContent = 'Belum ada pasien dipilih';
                fields.rm.textContent = 'No. RM -';
                fields.jk.textContent = '-';
                fields.lahir.textContent = '-';
                fields.golongan.textContent = '-';
                fields.jaminan.textContent = '-';
                fields.penanggungjawab.textContent = '-';
                fields.alergi.textContent = '-';
            };

            const updatePreview = () => {
                const selected = select.options[select.selectedIndex];
                if (! selected || ! selected.value) {
                    setEmpty();
                    return;
                }

                fields.name.textContent = selected.dataset.nama || '-';
                fields.rm.textContent = `No. RM ${selected.dataset.rm || '-'}`;
                fields.jk.textContent = selected.dataset.jk || '-';
                fields.lahir.textContent = selected.dataset.lahir || '-';
                fields.golongan.textContent = selected.dataset.golongan || '-';
                fields.jaminan.textContent = selected.dataset.jaminan || '-';
                fields.penanggungjawab.textContent = selected.dataset.penanggungjawab || '-';
                fields.alergi.textContent = selected.dataset.alergi || '-';
            };

            select.addEventListener('change', updatePreview);
            updatePreview();
        })();
    </script>
@endsection
