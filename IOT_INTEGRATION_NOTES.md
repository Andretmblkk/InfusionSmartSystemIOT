# IoT Integration Notes - VitalFlow

Dokumen ini mencatat hal yang sudah disiapkan di Laravel dan hal yang harus dikonfirmasi setelah project PHP native IoT tersedia.

## Status Laravel Saat Ini

- Login, dashboard, input pasien, monitoring, dan riwayat placeholder sudah tersedia.
- Data pasien tersimpan di tabel `patients`.
- Dashboard dan monitoring sudah membaca data pasien dari database.
- Endpoint kompatibilitas alat sudah tersedia di `POST /api/api.php`.
- Endpoint modern juga tersedia di `POST /api/infusion-readings`.
- Data sensor tersimpan di tabel `infusion_readings`.
- Sesi alat/node tersimpan di tabel `infusion_monitorings`.
- Dashboard, monitoring, detail pasien, dan riwayat akan memakai data sensor terbaru jika tersedia; jika belum ada data alat, UI tetap fallback ke data presentasi dari `InfusionDisplayService`.
- Seeder membuat monitoring aktif untuk node 1, 2, dan 3.

## Hal Yang Harus Dibedah Dari PHP Native

- Endpoint lama yang dipanggil alat IoT: `POST /api/api.php`.
- Method HTTP: `POST`.
- Format request: JSON atau form-urlencoded.
- Parameter utama: `node`, `berat`, `volume`/`persen`, `laju`/`tpm`, `status_infus`/`status`, opsional `status_tetesan`.
- Response: JSON dengan field `success`, `message`, `node`, dan `waktu`.
- Native menentukan monitoring aktif dari `node` 1-3 yang cocok dengan `unit_infus`.
- Status alat: `normal`, `macet`, `habis`; `status_tetesan=terhambat` dipetakan ke `macet`.

## Mapping Laravel Yang Kemungkinan Dibutuhkan

- `infusion_monitorings`: hubungan pasien, node alat, kapasitas, waktu pemasangan, dan status.
- `infusion_readings`: log berat/pembacaan dari alat.
- `Api\InfusionReadingController`: menerima payload alat.
- `InfusionCalculator`: normalisasi status, persentase, dan estimasi waktu.
- Route kompatibilitas endpoint lama tersedia agar firmware tidak perlu banyak diubah.

## Endpoint Firmware

Jika Laravel dijalankan di komputer dengan IP LAN `192.168.1.3` dan port `8000`, arahkan alat ke:

```text
http://192.168.1.3:8000/api/api.php
```

Contoh payload:

```json
{
  "node": 1,
  "berat": 343.6,
  "volume": 68.7,
  "laju": 25,
  "status_infus": "normal"
}
```

Response sukses:

```json
{
  "success": true,
  "message": "Data Berhasil",
  "node": 1,
  "waktu": "2026-06-08 12:33:04"
}
```

Catatan runtime: project memakai MySQL sesuai `.env`. Pastikan MySQL aktif di `127.0.0.1:3306`, database `monitoring_infus_rumah_sakit_yowari` tersedia, lalu jalankan `php artisan migrate --seed` sebelum alat dipakai.

## Prinsip Integrasi

- Kontrak API alat dari PHP native harus jadi sumber kebenaran.
- Endpoint Laravel sebaiknya bisa meniru endpoint lama jika firmware sulit diubah.
- Dashboard Blade yang ada harus dipertahankan dan hanya sumber datanya diganti.
- Jangan mengganti desain visual utama saat integrasi IoT.
- Tambahkan test API untuk setiap endpoint alat.
