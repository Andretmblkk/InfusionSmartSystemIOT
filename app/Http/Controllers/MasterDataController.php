<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\InfusionProduct;
use App\Models\Nurse;
use App\Models\RegisteredPatient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function index(): View
    {
        $patientDisplayLimit = 16;
        $referenceDisplayLimit = 8;

        return view('pages.master-data', [
            'patientCount' => RegisteredPatient::count(),
            'doctorCount' => Doctor::count(),
            'nurseCount' => Nurse::count(),
            'infusionCount' => InfusionProduct::count(),
            'registeredPatients' => RegisteredPatient::latest('id')->limit($patientDisplayLimit)->get(),
            'doctors' => Doctor::orderBy('nama_lengkap')->limit($referenceDisplayLimit)->get(),
            'nurses' => Nurse::orderBy('nama_lengkap')->limit($referenceDisplayLimit)->get(),
            'infusionProducts' => InfusionProduct::orderBy('nama')->limit($referenceDisplayLimit)->get(),
        ]);
    }

    public function storePatient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pasien_nomor_rekam_medis' => ['required', 'string', 'max:50', Rule::unique('data_pasien', 'nomor_rekam_medis')],
            'pasien_nik' => ['nullable', 'string', 'max:30'],
            'pasien_nama_lengkap' => ['required', 'string', 'max:255'],
            'pasien_jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'pasien_tempat_lahir' => ['nullable', 'string', 'max:100'],
            'pasien_tanggal_lahir' => ['nullable', 'date'],
            'pasien_golongan_darah' => ['nullable', Rule::in(['A', 'B', 'AB', 'O'])],
            'pasien_alergi' => ['nullable', 'string', 'max:1000'],
            'pasien_alamat' => ['nullable', 'string', 'max:1000'],
            'pasien_telepon' => ['nullable', 'string', 'max:30'],
            'pasien_nama_penanggung_jawab' => ['nullable', 'string', 'max:255'],
            'pasien_telepon_penanggung_jawab' => ['nullable', 'string', 'max:30'],
            'pasien_jenis_jaminan' => ['nullable', 'string', 'max:100'],
        ]);

        RegisteredPatient::create([
            'nomor_rekam_medis' => $validated['pasien_nomor_rekam_medis'],
            'nik' => $validated['pasien_nik'] ?? null,
            'nama_lengkap' => $validated['pasien_nama_lengkap'],
            'jenis_kelamin' => $validated['pasien_jenis_kelamin'] ?? null,
            'tempat_lahir' => $validated['pasien_tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['pasien_tanggal_lahir'] ?? null,
            'golongan_darah' => $validated['pasien_golongan_darah'] ?? null,
            'alergi' => $validated['pasien_alergi'] ?? null,
            'alamat' => $validated['pasien_alamat'] ?? null,
            'telepon' => $validated['pasien_telepon'] ?? null,
            'nama_penanggung_jawab' => $validated['pasien_nama_penanggung_jawab'] ?? null,
            'telepon_penanggung_jawab' => $validated['pasien_telepon_penanggung_jawab'] ?? null,
            'jenis_jaminan' => $validated['pasien_jenis_jaminan'] ?? null,
        ]);

        return back()->with('status', 'Data pasien terdaftar berhasil disimpan.');
    }

    public function storeDoctor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dokter_nomor_pegawai' => ['nullable', 'string', 'max:50', Rule::unique('data_dokter', 'nomor_pegawai')->whereNotNull('nomor_pegawai')],
            'dokter_nama_lengkap' => ['required', 'string', 'max:255'],
            'dokter_spesialis' => ['nullable', 'string', 'max:100'],
            'dokter_unit' => ['nullable', 'string', 'max:100'],
            'dokter_telepon' => ['nullable', 'string', 'max:30'],
        ]);

        Doctor::create([
            'nomor_pegawai' => $validated['dokter_nomor_pegawai'] ?? null,
            'nama_lengkap' => $validated['dokter_nama_lengkap'],
            'spesialis' => $validated['dokter_spesialis'] ?? null,
            'unit' => $validated['dokter_unit'] ?? null,
            'telepon' => $validated['dokter_telepon'] ?? null,
        ]);

        return back()->with('status', 'Data dokter berhasil disimpan.');
    }

    public function storeNurse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'perawat_nomor_pegawai' => ['nullable', 'string', 'max:50', Rule::unique('data_perawat', 'nomor_pegawai')->whereNotNull('nomor_pegawai')],
            'perawat_nama_lengkap' => ['required', 'string', 'max:255'],
            'perawat_unit' => ['nullable', 'string', 'max:100'],
            'perawat_telepon' => ['nullable', 'string', 'max:30'],
        ]);

        Nurse::create([
            'nomor_pegawai' => $validated['perawat_nomor_pegawai'] ?? null,
            'nama_lengkap' => $validated['perawat_nama_lengkap'],
            'unit' => $validated['perawat_unit'] ?? null,
            'telepon' => $validated['perawat_telepon'] ?? null,
        ]);

        return back()->with('status', 'Data perawat berhasil disimpan.');
    }

    public function storeInfusionProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'infus_nama' => ['required', 'string', 'max:255'],
            'infus_kategori' => ['nullable', 'string', 'max:100'],
            'infus_volume_default_ml' => ['required', 'integer', 'min:1', 'max:5000'],
            'infus_pabrikan' => ['nullable', 'string', 'max:255'],
            'infus_catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        InfusionProduct::create([
            'nama' => $validated['infus_nama'],
            'kategori' => $validated['infus_kategori'] ?? null,
            'volume_default_ml' => (int) $validated['infus_volume_default_ml'],
            'pabrikan' => $validated['infus_pabrikan'] ?? null,
            'catatan' => $validated['infus_catatan'] ?? null,
        ]);

        return back()->with('status', 'Data infus berhasil disimpan.');
    }
}
