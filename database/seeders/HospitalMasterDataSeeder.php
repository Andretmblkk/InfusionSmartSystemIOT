<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\InfusionProduct;
use App\Models\Nurse;
use App\Models\RegisteredPatient;
use Illuminate\Database\Seeder;

class HospitalMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->patients() as $patient) {
            RegisteredPatient::updateOrCreate(
                ['nomor_rekam_medis' => $patient['nomor_rekam_medis']],
                $patient,
            );
        }

        foreach ($this->doctors() as $doctor) {
            Doctor::updateOrCreate(
                ['nomor_pegawai' => $doctor['nomor_pegawai']],
                $doctor,
            );
        }

        foreach ($this->nurses() as $nurse) {
            Nurse::updateOrCreate(
                ['nomor_pegawai' => $nurse['nomor_pegawai']],
                $nurse,
            );
        }

        foreach ($this->infusions() as $infusion) {
            InfusionProduct::updateOrCreate(
                ['nama' => $infusion['nama'], 'volume_default_ml' => $infusion['volume_default_ml']],
                $infusion,
            );
        }
    }

    private function patients(): array
    {
        return [
            [
                'nomor_rekam_medis' => 'RM-2026-0001',
                'nik' => '9101010101010001',
                'nama_lengkap' => 'Andre Tumbelaka',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jayapura',
                'tanggal_lahir' => '2002-08-12',
                'golongan_darah' => 'O',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Sentani, Kabupaten Jayapura',
                'telepon' => '081234567801',
                'nama_penanggung_jawab' => 'Keluarga Andre',
                'telepon_penanggung_jawab' => '081234567802',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0002',
                'nik' => '9101010101010002',
                'nama_lengkap' => 'Maria Yoku',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Wamena',
                'tanggal_lahir' => '1998-02-20',
                'golongan_darah' => 'A',
                'alergi' => 'Alergi penisilin',
                'alamat' => 'Waena, Kota Jayapura',
                'telepon' => '081234567803',
                'nama_penanggung_jawab' => 'Keluarga Maria',
                'telepon_penanggung_jawab' => '081234567804',
                'jenis_jaminan' => 'Umum',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0003',
                'nik' => '9101010101010003',
                'nama_lengkap' => 'Yohanis Kogoya',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Wamena',
                'tanggal_lahir' => '1987-11-05',
                'golongan_darah' => 'B',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Abepura, Kota Jayapura',
                'telepon' => '081234567805',
                'nama_penanggung_jawab' => 'Marta Kogoya',
                'telepon_penanggung_jawab' => '081234567806',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0004',
                'nik' => '9101010101010004',
                'nama_lengkap' => 'Elsye Wenda',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Timika',
                'tanggal_lahir' => '1991-03-18',
                'golongan_darah' => 'O',
                'alergi' => 'Alergi cefadroxil',
                'alamat' => 'Hamadi, Kota Jayapura',
                'telepon' => '081234567807',
                'nama_penanggung_jawab' => 'Petrus Wenda',
                'telepon_penanggung_jawab' => '081234567808',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0005',
                'nik' => '9101010101010005',
                'nama_lengkap' => 'Rinto Rumbarar',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Biak',
                'tanggal_lahir' => '1979-09-27',
                'golongan_darah' => 'A',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Entrop, Kota Jayapura',
                'telepon' => '081234567809',
                'nama_penanggung_jawab' => 'Mina Rumbarar',
                'telepon_penanggung_jawab' => '081234567810',
                'jenis_jaminan' => 'Asuransi Mandiri',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0006',
                'nik' => '9101010101010006',
                'nama_lengkap' => 'Meyke Ayorbaba',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Serui',
                'tanggal_lahir' => '2000-01-14',
                'golongan_darah' => 'AB',
                'alergi' => 'Alergi makanan laut',
                'alamat' => 'Heram, Kota Jayapura',
                'telepon' => '081234567811',
                'nama_penanggung_jawab' => 'Daniel Ayorbaba',
                'telepon_penanggung_jawab' => '081234567812',
                'jenis_jaminan' => 'Umum',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0007',
                'nik' => '9101010101010007',
                'nama_lengkap' => 'Samuel Pekei',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Nabire',
                'tanggal_lahir' => '1968-07-09',
                'golongan_darah' => 'O',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Sentani Timur, Kabupaten Jayapura',
                'telepon' => '081234567813',
                'nama_penanggung_jawab' => 'Yuliana Pekei',
                'telepon_penanggung_jawab' => '081234567814',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0008',
                'nik' => '9101010101010008',
                'nama_lengkap' => 'Lidia Mayor',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Jayapura',
                'tanggal_lahir' => '1996-12-02',
                'golongan_darah' => 'B',
                'alergi' => 'Alergi amoksisilin',
                'alamat' => 'Kotaraja, Kota Jayapura',
                'telepon' => '081234567815',
                'nama_penanggung_jawab' => 'Simon Mayor',
                'telepon_penanggung_jawab' => '081234567816',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0009',
                'nik' => '9101010101010009',
                'nama_lengkap' => 'Paskalis Matuan',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Merauke',
                'tanggal_lahir' => '1984-05-30',
                'golongan_darah' => 'A',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Abe Pantai, Kota Jayapura',
                'telepon' => '081234567817',
                'nama_penanggung_jawab' => 'Theresia Matuan',
                'telepon_penanggung_jawab' => '081234567818',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0010',
                'nik' => '9101010101010010',
                'nama_lengkap' => 'Desi Marthen',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Sarmi',
                'tanggal_lahir' => '1993-08-21',
                'golongan_darah' => 'O',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Waena, Kota Jayapura',
                'telepon' => '081234567819',
                'nama_penanggung_jawab' => 'Agus Marthen',
                'telepon_penanggung_jawab' => '081234567820',
                'jenis_jaminan' => 'JKN-KIS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0011',
                'nik' => '9101010101010011',
                'nama_lengkap' => 'Yosef Yarangga',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Dekai',
                'tanggal_lahir' => '1975-10-11',
                'golongan_darah' => 'B',
                'alergi' => 'Alergi ibuprofen',
                'alamat' => 'Sentani, Kabupaten Jayapura',
                'telepon' => '081234567821',
                'nama_penanggung_jawab' => 'Martha Yarangga',
                'telepon_penanggung_jawab' => '081234567822',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0012',
                'nik' => '9101010101010012',
                'nama_lengkap' => 'Kristina Imbiri',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Biak',
                'tanggal_lahir' => '1989-06-16',
                'golongan_darah' => 'AB',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Dok IX, Kota Jayapura',
                'telepon' => '081234567823',
                'nama_penanggung_jawab' => 'Obed Imbiri',
                'telepon_penanggung_jawab' => '081234567824',
                'jenis_jaminan' => 'Askes',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0013',
                'nik' => '9101010101010013',
                'nama_lengkap' => 'Petrus Rumbiak',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Manokwari',
                'tanggal_lahir' => '1962-02-08',
                'golongan_darah' => 'O',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Skyland, Kota Jayapura',
                'telepon' => '081234567825',
                'nama_penanggung_jawab' => 'Martha Rumbiak',
                'telepon_penanggung_jawab' => '081234567826',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0014',
                'nik' => '9101010101010014',
                'nama_lengkap' => 'Meryani Awi',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Keerom',
                'tanggal_lahir' => '2001-04-25',
                'golongan_darah' => 'A',
                'alergi' => 'Alergi lateks',
                'alamat' => 'Yabansai, Heram',
                'telepon' => '081234567827',
                'nama_penanggung_jawab' => 'Yance Awi',
                'telepon_penanggung_jawab' => '081234567828',
                'jenis_jaminan' => 'Umum',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0015',
                'nik' => '9101010101010015',
                'nama_lengkap' => 'Dominggus Mote',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Paniai',
                'tanggal_lahir' => '1971-01-19',
                'golongan_darah' => 'B',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Tanah Hitam, Abepura',
                'telepon' => '081234567829',
                'nama_penanggung_jawab' => 'Mina Mote',
                'telepon_penanggung_jawab' => '081234567830',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0016',
                'nik' => '9101010101010016',
                'nama_lengkap' => 'Rina Kafiar',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Fakfak',
                'tanggal_lahir' => '1997-09-13',
                'golongan_darah' => 'O',
                'alergi' => 'Alergi parasetamol sirup tertentu',
                'alamat' => 'Padang Bulan, Abepura',
                'telepon' => '081234567831',
                'nama_penanggung_jawab' => 'Toni Kafiar',
                'telepon_penanggung_jawab' => '081234567832',
                'jenis_jaminan' => 'BPJS',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0017',
                'nik' => '9101010101010017',
                'nama_lengkap' => 'Agustinus Mofu',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Sentani',
                'tanggal_lahir' => '1982-07-04',
                'golongan_darah' => 'AB',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Ifar Besar, Kabupaten Jayapura',
                'telepon' => '081234567833',
                'nama_penanggung_jawab' => 'Maria Mofu',
                'telepon_penanggung_jawab' => '081234567834',
                'jenis_jaminan' => 'Jasa Raharja',
            ],
            [
                'nomor_rekam_medis' => 'RM-2026-0018',
                'nik' => '9101010101010018',
                'nama_lengkap' => 'Helena Yapen',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Serui',
                'tanggal_lahir' => '1994-11-28',
                'golongan_darah' => 'A',
                'alergi' => 'Tidak ada alergi yang diketahui',
                'alamat' => 'Asei Kecil, Sentani Timur',
                'telepon' => '081234567835',
                'nama_penanggung_jawab' => 'Markus Yapen',
                'telepon_penanggung_jawab' => '081234567836',
                'jenis_jaminan' => 'BPJS',
            ],
        ];
    }

    private function doctors(): array
    {
        return [
            ['nomor_pegawai' => 'DR-001', 'nama_lengkap' => 'dr. Andre', 'spesialis' => 'Sp.PD', 'unit' => 'Rawat Inap Penyakit Dalam', 'telepon' => '081234567901'],
            ['nomor_pegawai' => 'DR-002', 'nama_lengkap' => 'dr. Yuliana M. Kambu', 'spesialis' => 'Sp.A', 'unit' => 'Rawat Inap Anak', 'telepon' => '081234567902'],
            ['nomor_pegawai' => 'DR-003', 'nama_lengkap' => 'dr. Petrus Wally', 'spesialis' => 'Sp.B', 'unit' => 'Bedah Umum', 'telepon' => '081234567903'],
            ['nomor_pegawai' => 'DR-004', 'nama_lengkap' => 'dr. Anita Rumainum', 'spesialis' => 'Sp.JP', 'unit' => 'Rawat Inap Jantung', 'telepon' => '081234567904'],
            ['nomor_pegawai' => 'DR-005', 'nama_lengkap' => 'dr. Markus Mote', 'spesialis' => 'Sp.An', 'unit' => 'Perioperatif', 'telepon' => '081234567905'],
            ['nomor_pegawai' => 'DR-006', 'nama_lengkap' => 'dr. Ruth A. Wonda', 'spesialis' => 'Sp.N', 'unit' => 'Rawat Inap Saraf', 'telepon' => '081234567906'],
            ['nomor_pegawai' => 'DR-007', 'nama_lengkap' => 'dr. Daniel Kossay', 'spesialis' => 'Sp.OG', 'unit' => 'Kebidanan dan Kandungan', 'telepon' => '081234567907'],
            ['nomor_pegawai' => 'DR-008', 'nama_lengkap' => 'dr. Debora Sroyer', 'spesialis' => 'Sp.KFR', 'unit' => 'Rehabilitasi Medik', 'telepon' => '081234567908'],
        ];
    }

    private function nurses(): array
    {
        return [
            ['nomor_pegawai' => 'PR-001', 'nama_lengkap' => 'Ns. Maria Kambu', 'unit' => 'Mawar 04', 'telepon' => '081234567921'],
            ['nomor_pegawai' => 'PR-002', 'nama_lengkap' => 'Ns. Rina Womsiwor', 'unit' => 'Mawar 04', 'telepon' => '081234567922'],
            ['nomor_pegawai' => 'PR-003', 'nama_lengkap' => 'Ns. Yulita Ayorbaba', 'unit' => 'Mawar 04', 'telepon' => '081234567923'],
            ['nomor_pegawai' => 'PR-004', 'nama_lengkap' => 'Ns. Markus Wenda', 'unit' => 'Mawar 04', 'telepon' => '081234567924'],
            ['nomor_pegawai' => 'PR-005', 'nama_lengkap' => 'Ns. Helena Rumbiak', 'unit' => 'Anggrek 02', 'telepon' => '081234567925'],
            ['nomor_pegawai' => 'PR-006', 'nama_lengkap' => 'Ns. Simon Mofu', 'unit' => 'Anggrek 02', 'telepon' => '081234567926'],
            ['nomor_pegawai' => 'PR-007', 'nama_lengkap' => 'Ns. Debby Kafiar', 'unit' => 'Cendrawasih 01', 'telepon' => '081234567927'],
            ['nomor_pegawai' => 'PR-008', 'nama_lengkap' => 'Ns. Paskal Kossay', 'unit' => 'Cendrawasih 01', 'telepon' => '081234567928'],
            ['nomor_pegawai' => 'PR-009', 'nama_lengkap' => 'Ns. Melda Yapen', 'unit' => 'Kenanga 03', 'telepon' => '081234567929'],
            ['nomor_pegawai' => 'PR-010', 'nama_lengkap' => 'Ns. Ruth Pekei', 'unit' => 'Kenanga 03', 'telepon' => '081234567930'],
            ['nomor_pegawai' => 'PR-011', 'nama_lengkap' => 'Ns. Beni Mayor', 'unit' => 'IGD Observasi', 'telepon' => '081234567931'],
            ['nomor_pegawai' => 'PR-012', 'nama_lengkap' => 'Ns. Agnes Marthen', 'unit' => 'Rawat Inap Anak', 'telepon' => '081234567932'],
        ];
    }

    private function infusions(): array
    {
        return [
            ['nama' => 'NaCl 0,9%', 'kategori' => 'Kristaloid', 'volume_default_ml' => 500, 'pabrikan' => 'Otsuka', 'catatan' => 'Cairan kristaloid isotonik.'],
            ['nama' => 'Ringer Laktat', 'kategori' => 'Kristaloid', 'volume_default_ml' => 500, 'pabrikan' => 'Otsuka', 'catatan' => 'Cairan pengganti elektrolit.'],
            ['nama' => 'Dextrose 5%', 'kategori' => 'Dekstrosa', 'volume_default_ml' => 500, 'pabrikan' => 'Generik', 'catatan' => 'Cairan glukosa 5%.'],
            ['nama' => 'NaCl 0,9%', 'kategori' => 'Kristaloid', 'volume_default_ml' => 1000, 'pabrikan' => 'Otsuka', 'catatan' => 'Digunakan untuk kebutuhan cairan volume besar.'],
            ['nama' => 'Ringer Laktat', 'kategori' => 'Kristaloid', 'volume_default_ml' => 1000, 'pabrikan' => 'Otsuka', 'catatan' => 'Sering digunakan pada rehidrasi dan pasca tindakan.'],
            ['nama' => 'Dextrose 5% + NaCl 0,45%', 'kategori' => 'Dekstrosa', 'volume_default_ml' => 500, 'pabrikan' => 'Widatra', 'catatan' => 'Untuk kebutuhan cairan pemeliharaan tertentu.'],
            ['nama' => 'KA-EN 3B', 'kategori' => 'Rumatan', 'volume_default_ml' => 500, 'pabrikan' => 'Otsuka', 'catatan' => 'Cairan rumatan dengan elektrolit dan glukosa.'],
            ['nama' => 'Asering', 'kategori' => 'Kristaloid', 'volume_default_ml' => 500, 'pabrikan' => 'Otsuka', 'catatan' => 'Alternatif cairan kristaloid seimbang.'],
            ['nama' => 'Aminofluid', 'kategori' => 'Nutrisi Parenteral', 'volume_default_ml' => 500, 'pabrikan' => 'B. Braun', 'catatan' => 'Larutan asam amino untuk dukungan nutrisi.'],
            ['nama' => 'Dextrose 10%', 'kategori' => 'Dekstrosa', 'volume_default_ml' => 500, 'pabrikan' => 'Generik', 'catatan' => 'Digunakan sesuai indikasi glukosa intravena.'],
            ['nama' => 'Gelafusal', 'kategori' => 'Koloid', 'volume_default_ml' => 500, 'pabrikan' => 'B. Braun', 'catatan' => 'Pengganti volume plasma sesuai indikasi dokter.'],
        ];
    }
}
