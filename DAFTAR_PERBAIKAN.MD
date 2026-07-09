# Daftar Perbaikan Fungsionalitas & UX - VitalFlow

Dokumen ini berisi daftar kekurangan fungsionalitas dan kegunaan (UX) yang diidentifikasi selama pengujian otomatis, beserta status implementasinya.

---

## Checklist Perbaikan

- [x] **1. Perbaikan Form Action Input Pasien (Kritis)**
  * **Lokasi File:** `resources/views/pages/patient-input.blade.php`
  * **Masalah:** Form sebelumnya menggunakan `action="#"`, sehingga tombol submit tidak mengirimkan data pasien ke backend Laravel/database.
  * **Perbaikan yang dilakukan:**
    1. Menambahkan model `App\Models\Patient`.
    2. Menambahkan migration tabel `patients`.
    3. Menambahkan `PatientController::create` dan `PatientController::store`.
    4. Menambahkan route:
       ```php
       Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
       Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
       ```
    5. Mengubah form menjadi:
       ```blade
       <form method="POST" action="{{ route('patients.store') }}">
       ```
    6. Submit berhasil menyimpan data pasien dan redirect ke halaman monitoring.

- [x] **2. Aktivasi Tombol Floating Tambah Pasien (`+`) di Dashboard**
  * **Lokasi File:** `resources/views/pages/dashboard.blade.php`
  * **Masalah:** Tombol melayang biru dengan ikon `+` sebelumnya berupa `<button type="button">` statis tanpa fungsi navigasi.
  * **Perbaikan yang dilakukan:**
    Tombol diubah menjadi tautan ke halaman input pasien:
    ```blade
    <a href="{{ route('patients.create') }}" class="...">
        <x-ui.icon name="plus" class="size-8" />
    </a>
    ```

- [x] **3. Optimasi Input Waktu Pemasangan (`installed_at`)**
  * **Lokasi File:** `resources/views/pages/patient-input.blade.php` dan `resources/views/components/dashboard/form-field.blade.php`
  * **Masalah:** Kolom **Waktu Pemasangan** sebelumnya memakai input text biasa, sehingga format waktu rawan salah.
  * **Perbaikan yang dilakukan:**
    1. Komponen `form-field` sudah mendukung atribut `type`.
    2. Field waktu pemasangan sekarang memakai input bawaan browser:
       ```blade
       <x-dashboard.form-field
           label="Waktu Pemasangan"
           name="installed_at"
           type="datetime-local"
           :value="old('installed_at', now()->format('Y-m-d\TH:i'))"
           required
       />
       ```
    3. Script E2E diperbarui agar mengisi format `datetime-local` valid: `2026-05-20T12:00`.

---

## Verifikasi

- [x] Migration `patients` berhasil dijalankan.
- [x] `php artisan route:list` menampilkan route `patients.store`.
- [x] `php artisan test` lulus dengan test submit pasien.
- [x] `npm.cmd run build` berhasil.
