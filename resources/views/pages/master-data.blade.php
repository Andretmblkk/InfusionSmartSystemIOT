@extends('layouts.dashboard')

@section('title', 'Input Data Pasien - VitalFlow')

@php
    $activeNav = 'master-data';
    $sidebarBrand = 'VitalFlow';
    $sidebarSubtitle = 'RSUD Yowari';
    $sidebarSupport = false;
    $sidebarVariant = 'patient-input';
    $sidebarSticky = true;
    $topbarTitle = 'Input Data Pasien';
    $topbarMode = 'patient-input';
@endphp

@section('content')
    <div class="mx-auto w-full max-w-[1180px] pb-10">
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-[30px] font-extrabold leading-tight tracking-[-0.035em] text-[#1f252c]">Input Data Pasien</h2>
                <p class="mt-[7px] max-w-[760px] text-[15px] font-medium leading-6 text-[#4d5561]">
                    Halaman ini dipakai untuk registrasi identitas pasien. Dokter, perawat, dan infus tetap tersedia untuk dipilih saat membuat monitoring.
                </p>
            </div>
            <a href="{{ route('patients.create') }}" class="inline-flex h-11 items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white shadow-[0_10px_18px_rgba(0,83,164,0.18)]">
                Input Monitoring
            </a>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Pasien Terdaftar</p>
                <p class="mt-2 text-[28px] font-extrabold text-[#173b80]">{{ $patientCount }}</p>
            </div>
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Dokter Tersedia</p>
                <p class="mt-2 text-[28px] font-extrabold text-[#173b80]">{{ $doctorCount }}</p>
            </div>
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Perawat Tersedia</p>
                <p class="mt-2 text-[28px] font-extrabold text-[#173b80]">{{ $nurseCount }}</p>
            </div>
            <div class="rounded-[7px] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#7b8794]">Jenis Infus</p>
                <p class="mt-2 text-[28px] font-extrabold text-[#173b80]">{{ $infusionCount }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[420px_minmax(0,1fr)]">
            <section class="rounded-[7px] bg-white p-7 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                <div class="mb-6 border-b border-[#edf0f3] pb-4">
                    <h3 class="text-[20px] font-extrabold text-[#1f252c]">Form Pasien</h3>
                    <p class="mt-1 text-[14px] font-medium leading-6 text-[#66717f]">Masukkan identitas pasien secara formal sebelum sesi monitoring dibuat.</p>
                </div>

                <form method="POST" action="{{ route('master-data.patients.store') }}" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    @csrf
                    <x-dashboard.form-field label="No. Rekam Medis" name="pasien_nomor_rekam_medis" :value="old('pasien_nomor_rekam_medis')" placeholder="RM-2026-0001" required />
                    <x-dashboard.form-field label="NIK" name="pasien_nik" :value="old('pasien_nik')" placeholder="16 digit" />
                    <x-dashboard.form-field label="Nama Lengkap Pasien" name="pasien_nama_lengkap" :value="old('pasien_nama_lengkap')" placeholder="Nama sesuai identitas" required />

                    <div class="space-y-[9px]">
                        <label for="pasien_jenis_kelamin" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Jenis Kelamin</label>
                        <select id="pasien_jenis_kelamin" name="pasien_jenis_kelamin" class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" @selected(old('pasien_jenis_kelamin') === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('pasien_jenis_kelamin') === 'P')>Perempuan</option>
                        </select>
                        @error('pasien_jenis_kelamin') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <x-dashboard.form-field label="Tempat Lahir" name="pasien_tempat_lahir" :value="old('pasien_tempat_lahir')" placeholder="Kota / kabupaten" />
                    <x-dashboard.form-field label="Tanggal Lahir" name="pasien_tanggal_lahir" type="date" :value="old('pasien_tanggal_lahir')" />

                    <div class="space-y-[9px]">
                        <label for="pasien_golongan_darah" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Golongan Darah</label>
                        <select id="pasien_golongan_darah" name="pasien_golongan_darah" class="h-12 w-full appearance-none rounded-[6px] border-0 bg-[#dfe4eb] px-4 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20">
                            <option value="">Belum diketahui</option>
                            @foreach (['A', 'B', 'AB', 'O'] as $golonganDarah)
                                <option value="{{ $golonganDarah }}" @selected(old('pasien_golongan_darah') === $golonganDarah)>{{ $golonganDarah }}</option>
                            @endforeach
                        </select>
                        @error('pasien_golongan_darah') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <x-dashboard.form-field label="No. Telepon" name="pasien_telepon" :value="old('pasien_telepon')" placeholder="08..." />
                    <x-dashboard.form-field label="Nama Penanggung Jawab" name="pasien_nama_penanggung_jawab" :value="old('pasien_nama_penanggung_jawab')" placeholder="Keluarga / wali pasien" />
                    <x-dashboard.form-field label="Telepon Penanggung Jawab" name="pasien_telepon_penanggung_jawab" :value="old('pasien_telepon_penanggung_jawab')" placeholder="08..." />
                    <x-dashboard.form-field label="Jenis Jaminan" name="pasien_jenis_jaminan" :value="old('pasien_jenis_jaminan')" placeholder="BPJS / Umum / Asuransi" />

                    <div class="space-y-[9px] sm:col-span-2">
                        <label for="pasien_alergi" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Alergi</label>
                        <textarea id="pasien_alergi" name="pasien_alergi" rows="2" class="w-full rounded-[6px] border-0 bg-[#dfe4eb] px-4 py-3 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20" placeholder="Contoh: tidak ada / alergi antibiotik tertentu">{{ old('pasien_alergi') }}</textarea>
                        @error('pasien_alergi') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-[9px] sm:col-span-2">
                        <label for="pasien_alamat" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">Alamat</label>
                        <textarea id="pasien_alamat" name="pasien_alamat" rows="3" class="w-full rounded-[6px] border-0 bg-[#dfe4eb] px-4 py-3 text-[16px] font-medium text-[#1f252c] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20" placeholder="Alamat domisili pasien">{{ old('pasien_alamat') }}</textarea>
                        @error('pasien_alamat') <p class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="sm:col-span-2 inline-flex h-11 items-center justify-center rounded-[7px] bg-[#005daa] px-5 text-[14px] font-extrabold text-white">
                        Simpan Data Pasien
                    </button>
                </form>
            </section>

            <section class="space-y-6">
                <div class="rounded-[7px] bg-white p-7 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                    <div class="mb-5 flex items-center justify-between gap-4 border-b border-[#edf0f3] pb-4">
                        <div>
                            <h3 class="text-[20px] font-extrabold text-[#1f252c]">Daftar Pasien</h3>
                            <p class="mt-1 text-[14px] font-medium leading-6 text-[#66717f]">Data pasien terbaru yang siap dipilih pada halaman input monitoring.</p>
                        </div>
                        <span class="rounded-[7px] bg-[#eef4fb] px-3 py-2 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#1152e8]">
                            {{ $registeredPatients->count() }} data
                        </span>
                    </div>

                    <div class="overflow-hidden rounded-[7px] border border-[#e6ebf1]">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#e6ebf1] text-left">
                                <thead class="bg-[#f7f9fb]">
                                    <tr>
                                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">No. RM</th>
                                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Nama Pasien</th>
                                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">JK</th>
                                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Golongan Darah</th>
                                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#66717f]">Jaminan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#edf0f3] bg-white">
                                    @forelse ($registeredPatients as $registeredPatient)
                                        <tr>
                                            <td class="px-4 py-3 text-[13px] font-bold text-[#173b80]">{{ $registeredPatient->nomor_rekam_medis }}</td>
                                            <td class="px-4 py-3">
                                                <p class="text-[13px] font-extrabold text-[#1f252c]">{{ $registeredPatient->nama_lengkap }}</p>
                                                <p class="mt-1 text-[12px] font-medium text-[#66717f]">{{ $registeredPatient->tempat_lahir ?: '-' }} / {{ $registeredPatient->tanggal_lahir?->format('d-m-Y') ?: '-' }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $registeredPatient->jenis_kelamin ?: '-' }}</td>
                                            <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $registeredPatient->golongan_darah ?: '-' }}</td>
                                            <td class="px-4 py-3 text-[13px] font-bold text-[#4d5561]">{{ $registeredPatient->jenis_jaminan ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-5 text-center text-[13px] font-bold text-[#66717f]">Belum ada data pasien terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="rounded-[7px] bg-white p-5 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                        <h4 class="text-[15px] font-extrabold text-[#1f252c]">Dokter Tersedia</h4>
                        <div class="mt-4 space-y-3">
                            @foreach ($doctors as $doctor)
                                <div class="rounded-[7px] bg-[#f7f9fb] px-4 py-3">
                                    <p class="text-[13px] font-extrabold text-[#1f252c]">{{ $doctor->nama_lengkap }}</p>
                                    <p class="mt-1 text-[12px] font-bold text-[#66717f]">{{ $doctor->spesialis ?: 'Dokter' }}{{ $doctor->unit ? ' | ' . $doctor->unit : '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[7px] bg-white p-5 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                        <h4 class="text-[15px] font-extrabold text-[#1f252c]">Perawat Tersedia</h4>
                        <div class="mt-4 space-y-3">
                            @foreach ($nurses as $nurse)
                                <div class="rounded-[7px] bg-[#f7f9fb] px-4 py-3">
                                    <p class="text-[13px] font-extrabold text-[#1f252c]">{{ $nurse->nama_lengkap }}</p>
                                    <p class="mt-1 text-[12px] font-bold text-[#66717f]">{{ $nurse->unit ?: 'Unit belum diisi' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[7px] bg-white p-5 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
                        <h4 class="text-[15px] font-extrabold text-[#1f252c]">Infus Tersedia</h4>
                        <div class="mt-4 space-y-3">
                            @foreach ($infusionProducts as $infusionProduct)
                                <div class="rounded-[7px] bg-[#f7f9fb] px-4 py-3">
                                    <p class="text-[13px] font-extrabold text-[#1f252c]">{{ $infusionProduct->nama }}</p>
                                    <p class="mt-1 text-[12px] font-bold text-[#66717f]">{{ $infusionProduct->volume_default_ml }} ml{{ $infusionProduct->kategori ? ' | ' . $infusionProduct->kategori : '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
