<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientDemoSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['patient_name' => 'Budi Santoso', 'room_name' => 'ICU Bed 04', 'bed_number' => 1, 'doctor_name' => 'dr. H. Sapto, Sp.A', 'nurse_name' => 'Suster Amira', 'initial_volume' => 500, 'installed_at' => '2026-05-20 08:00:00'],
            ['patient_name' => 'Siti Aminah', 'room_name' => 'Ruang 105', 'bed_number' => 2, 'doctor_name' => 'dr. Sp.PD Amelia', 'nurse_name' => 'Suster Rina', 'initial_volume' => 1000, 'installed_at' => '2026-05-20 08:15:00'],
            ['patient_name' => 'Dahlan Iskan', 'room_name' => 'Ruang ICU-1', 'bed_number' => 3, 'doctor_name' => 'dr. Sp.JP Kurniawan', 'nurse_name' => 'Suster Amira', 'initial_volume' => 500, 'installed_at' => '2026-05-20 08:30:00'],
            ['patient_name' => 'Rina Wijaya', 'room_name' => 'Ruang 405A', 'doctor_name' => 'dr. Sp.A Melati', 'nurse_name' => 'Suster Dewi', 'initial_volume' => 500, 'installed_at' => '2026-05-20 08:45:00'],
            ['patient_name' => 'Ahmad Zaky', 'room_name' => 'Ruang 210', 'doctor_name' => 'dr. Sp.An Farhan', 'nurse_name' => 'Suster Rina', 'initial_volume' => 1000, 'installed_at' => '2026-05-20 09:00:00'],
            ['patient_name' => 'Dewi Lestari', 'room_name' => 'Ruang 308', 'doctor_name' => 'dr. Sp.PD Hidayat', 'nurse_name' => 'Suster Amira', 'initial_volume' => 500, 'installed_at' => '2026-05-20 09:15:00'],
            ['patient_name' => 'Maya Putri', 'room_name' => 'Ruang 212', 'doctor_name' => 'dr. Sp.An Farhan', 'nurse_name' => 'Suster Dewi', 'initial_volume' => 500, 'installed_at' => '2026-05-20 09:30:00'],
            ['patient_name' => 'Hendra Saputra', 'room_name' => 'Ruang 309', 'doctor_name' => 'dr. Sp.PD Hidayat', 'nurse_name' => 'Suster Rina', 'initial_volume' => 1000, 'installed_at' => '2026-05-20 09:45:00'],
        ];

        foreach ($patients as $patient) {
            Patient::updateOrCreate(
                [
                    'patient_name' => $patient['patient_name'],
                    'room_name' => $patient['room_name'],
                ],
                $patient,
            );
        }
    }
}
