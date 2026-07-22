<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\InfusionProduct;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\RegisteredPatient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_seeded_user_can_login_with_employee_id(): void
    {
        $this->perawatUser();

        $response = $this->post('/login', [
            'employee_id' => '2405001',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_authenticated_user_can_view_monitoring_page(): void
    {
        $user = $this->perawatUser();
        $patient = $this->activePatient('Feature Monitoring Pasien', 1, 2);

        $response = $this->actingAs($user)->get('/monitoring');

        $response
            ->assertStatus(200)
            ->assertSee('Monitoring Infus')
            ->assertSee('Feature Monitoring Pasien')
            ->assertSee('Pasien Baru');

        Patient::whereKey($patient->id)->delete();
    }

    public function test_authenticated_user_can_view_history_page(): void
    {
        $user = $this->perawatUser();

        $response = $this->actingAs($user)->get('/monitoring/reports');

        $response
            ->assertStatus(200)
            ->assertSee('Laporan Monitoring');
    }

    public function test_authenticated_user_can_view_patient_detail_page(): void
    {
        $user = $this->perawatUser();
        $patient = $this->activePatient('Feature Detail Pasien', 1, 2);

        $response = $this->actingAs($user)->get(route('patients.show', $patient));

        $response
            ->assertStatus(200)
            ->assertSee('Feature Detail Pasien')
            ->assertSee('Ganti Infus');
    }

    public function test_authenticated_user_can_store_patient_data(): void
    {
        $user = $this->perawatUser();
        $refs = $this->hospitalReferences('Feature Submit Pasien');

        $response = $this->actingAs($user)->post('/patients', [
            'data_pasien_id' => $refs['patient']->id,
            'room_name' => 'VIP Dewasa',
            'bed_number' => 1,
            'data_dokter_id' => $refs['doctor']->id,
            'data_perawat_id' => $refs['nurse']->id,
            'data_infus_id' => $refs['infusion']->id,
            'initial_volume' => 500,
            'installed_at' => '2026-05-20T12:00',
        ]);

        $response->assertRedirect(route('monitoring'));

        $this->assertDatabaseHas('patients', [
            'data_pasien_id' => $refs['patient']->id,
            'patient_name' => 'Feature Submit Pasien',
            'room_name' => 'VIP Dewasa',
            'bed_number' => 1,
            'initial_volume' => 500,
        ]);

        $this->assertDatabaseHas('infusion_monitorings', [
            'node_id' => 2,
            'bed_number' => 1,
            'unit_infus' => 'Bed 1',
            'capacity_ml' => 500,
            'status' => 'aktif',
        ]);
    }

    public function test_authenticated_user_can_replace_infusion_for_same_bed(): void
    {
        $user = $this->perawatUser();
        $refs = $this->hospitalReferences('Feature Replace Pasien');
        $patient = $this->activePatient('Feature Replace Pasien', 1, 2, $refs);
        $oldMonitoring = $patient->infusionMonitorings()->firstOrFail();
        $replacementInfusion = InfusionProduct::create([
            'nama' => 'NaCl 0,9% 1000 ml',
            'kategori' => 'Kristaloid',
            'volume_default_ml' => 1000,
            'aktif' => true,
        ]);

        $response = $this->actingAs($user)->post(route('patients.replace-infusion', $patient), [
            'pengganti_data_infus_id' => $replacementInfusion->id,
            'replacement_volume' => 1000,
            'pengganti_data_perawat_id' => $refs['nurse']->id,
            'replaced_at' => '2026-05-20T14:30',
        ]);

        $response->assertRedirect(route('patients.show', $patient));

        $this->assertDatabaseHas('infusion_monitorings', [
            'id' => $oldMonitoring->id,
            'status' => 'diganti',
        ]);
        $this->assertDatabaseHas('infusion_monitorings', [
            'patient_id' => $patient->id,
            'node_id' => 2,
            'bed_number' => 1,
            'capacity_ml' => 1000,
            'status' => 'aktif',
        ]);
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'initial_volume' => 1000,
        ]);
    }

    public function test_storing_patient_reassigns_selected_bed_to_new_patient(): void
    {
        $user = $this->perawatUser();
        $oldPatient = $this->activePatient('Feature Pasien Lama', 1, 2);
        $oldMonitoring = $oldPatient->infusionMonitorings()->firstOrFail();
        $refs = $this->hospitalReferences('Feature Pasien Baru');

        $response = $this->actingAs($user)->post('/patients', [
            'data_pasien_id' => $refs['patient']->id,
            'room_name' => 'VIP Dewasa',
            'bed_number' => 1,
            'confirm_bed_transfer' => 1,
            'data_dokter_id' => $refs['doctor']->id,
            'data_perawat_id' => $refs['nurse']->id,
            'data_infus_id' => $refs['infusion']->id,
            'initial_volume' => 500,
            'installed_at' => '2026-05-20T12:00',
        ]);

        $response->assertRedirect(route('monitoring'));

        $newPatient = Patient::where('patient_name', 'Feature Pasien Baru')->firstOrFail();

        $this->assertDatabaseHas('infusion_monitorings', [
            'id' => $oldMonitoring->id,
            'status' => 'selesai',
        ]);
        $this->assertDatabaseHas('infusion_monitorings', [
            'patient_id' => $newPatient->id,
            'node_id' => 2,
            'bed_number' => 1,
            'status' => 'aktif',
        ]);
    }

    public function test_storing_patient_requires_confirmation_when_bed_is_occupied(): void
    {
        $user = $this->perawatUser();
        $this->activePatient('Feature Pasien Aktif', 1, 2);
        $refs = $this->hospitalReferences('Feature Pasien Baru Tanpa Konfirmasi');

        $response = $this->actingAs($user)->from('/patients/create')->post('/patients', [
            'data_pasien_id' => $refs['patient']->id,
            'room_name' => 'VIP Dewasa',
            'bed_number' => 1,
            'data_dokter_id' => $refs['doctor']->id,
            'data_perawat_id' => $refs['nurse']->id,
            'data_infus_id' => $refs['infusion']->id,
            'initial_volume' => 500,
            'installed_at' => '2026-05-20T12:00',
        ]);

        $response
            ->assertRedirect('/patients/create')
            ->assertSessionHasErrors('bed_number');

        $this->assertDatabaseMissing('patients', [
            'patient_name' => 'Feature Pasien Baru Tanpa Konfirmasi',
        ]);
    }

    private function perawatUser(): User
    {
        return User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );
    }

    private function activePatient(string $name, int $bedNumber, int $nodeId, ?array $refs = null): Patient
    {
        $refs ??= $this->hospitalReferences($name);

        $patient = Patient::create([
            'data_pasien_id' => $refs['patient']->id,
            'data_dokter_id' => $refs['doctor']->id,
            'data_perawat_id' => $refs['nurse']->id,
            'patient_name' => $name,
            'room_name' => 'VIP Dewasa',
            'bed_number' => $bedNumber,
            'doctor_name' => $refs['doctor']->nama_lengkap,
            'nurse_name' => $refs['nurse']->nama_lengkap,
            'initial_volume' => 500,
            'installed_at' => '2026-05-20 12:00:00',
        ]);

        $patient->infusionMonitorings()->create([
            'node_id' => $nodeId,
            'bed_number' => $bedNumber,
            'unit_infus' => 'Bed ' . $bedNumber,
            'infusion_name' => $refs['infusion']->nama,
            'data_infus_id' => $refs['infusion']->id,
            'capacity_ml' => 500,
            'responsible_nurse' => $refs['nurse']->nama_lengkap,
            'perawat_penanggung_jawab_id' => $refs['nurse']->id,
            'started_at' => '2026-05-20 12:00:00',
            'status' => 'aktif',
        ]);

        return $patient->load('infusionMonitorings');
    }

    private function hospitalReferences(string $patientName): array
    {
        $suffix = substr(md5($patientName), 0, 8);

        return [
            'patient' => RegisteredPatient::create([
                'nomor_rekam_medis' => 'RM-' . $suffix,
                'nama_lengkap' => $patientName,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
                'jenis_jaminan' => 'BPJS',
                'aktif' => true,
            ]),
            'doctor' => Doctor::create([
                'nomor_pegawai' => 'DR-' . $suffix,
                'nama_lengkap' => 'dr. Feature',
                'spesialis' => 'Sp.PD',
                'unit' => 'VIP Dewasa',
                'aktif' => true,
            ]),
            'nurse' => Nurse::create([
                'nomor_pegawai' => 'PR-' . $suffix,
                'nama_lengkap' => 'Suster Feature',
                'unit' => 'VIP Dewasa',
                'aktif' => true,
            ]),
            'infusion' => InfusionProduct::create([
                'nama' => 'Ringer Laktat 500 ml',
                'kategori' => 'Kristaloid',
                'volume_default_ml' => 500,
                'aktif' => true,
            ]),
        ];
    }
}
