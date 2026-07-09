<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_seeded_user_can_login_with_employee_id(): void
    {
        User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        $response = $this->post('/login', [
            'employee_id' => '2405001',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_authenticated_user_can_view_monitoring_page(): void
    {
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        Patient::where('patient_name', 'like', 'Feature%')->delete();

        $patient = Patient::create([
            'patient_name' => 'Feature Monitoring Pasien',
            'room_name' => 'Feature Bed 01',
            'bed_number' => 1,
            'doctor_name' => 'dr. H. Sapto, Sp.A',
            'nurse_name' => 'Suster Amira',
            'initial_volume' => 500,
            'installed_at' => '2026-05-20 12:00:00',
        ]);
        $patient->infusionMonitorings()->create([
            'node_id' => 1,
            'bed_number' => 1,
            'unit_infus' => 'Kasur 1',
            'capacity_ml' => 500,
            'started_at' => '2026-05-20 12:00:00',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get('/monitoring');

        $response
            ->assertStatus(200)
            ->assertSee('Monitoring Infus')
            ->assertSee('Feature Monitoring Pasien')
            ->assertSee('Pasien Baru');

        Patient::where('patient_name', 'like', 'Feature%')->delete();
    }

    public function test_authenticated_user_can_view_history_page(): void
    {
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        $response = $this->actingAs($user)->get('/monitoring/history');

        $response
            ->assertStatus(200)
            ->assertSee('Riwayat Monitoring')
            ->assertSee('Log Pembacaan Terakhir');
    }

    public function test_authenticated_user_can_view_patient_detail_page(): void
    {
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        Patient::where('patient_name', 'like', 'Feature%')->delete();

        $patient = Patient::create([
            'patient_name' => 'Feature Detail Pasien',
            'room_name' => 'Ruang Detail',
            'bed_number' => 1,
            'doctor_name' => 'dr. Detail, Sp.A',
            'nurse_name' => 'Suster Amira',
            'initial_volume' => 500,
            'installed_at' => '2026-05-20 12:00:00',
        ]);
        $patient->infusionMonitorings()->create([
            'node_id' => 1,
            'bed_number' => 1,
            'unit_infus' => 'Kasur 1',
            'capacity_ml' => 500,
            'started_at' => '2026-05-20 12:00:00',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get(route('patients.show', $patient));

        $response
            ->assertStatus(200)
            ->assertSee('Feature Detail Pasien')
            ->assertSee('Catatan Integrasi IoT');

        Patient::where('patient_name', 'like', 'Feature%')->delete();
    }

    public function test_authenticated_user_can_store_patient_data(): void
    {
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        $response = $this->actingAs($user)->post('/patients', [
            'patient_name' => 'Feature Submit Pasien',
            'room_name' => 'ICU Bed 04',
            'bed_number' => 1,
            'doctor_name' => 'dr. H. Sapto, Sp.A',
            'nurse_name' => 'Suster Amira',
            'initial_volume' => 500,
            'installed_at' => '2026-05-20T12:00',
        ]);

        $response->assertRedirect(route('monitoring'));

        $this->assertDatabaseHas('patients', [
            'patient_name' => 'Feature Submit Pasien',
            'room_name' => 'ICU Bed 04',
            'bed_number' => 1,
            'initial_volume' => 500,
        ]);

        $this->assertDatabaseHas('infusion_monitorings', [
            'node_id' => 1,
            'bed_number' => 1,
            'unit_infus' => 'Kasur 1',
            'capacity_ml' => 500,
            'status' => 'aktif',
        ]);

        Patient::where('patient_name', 'like', 'Feature%')->delete();
    }

    public function test_authenticated_user_can_replace_infusion_for_same_bed(): void
    {
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        $patient = Patient::create([
            'patient_name' => 'Feature Replace Pasien',
            'room_name' => 'Ruang Replace',
            'bed_number' => 2,
            'doctor_name' => 'dr. Replace',
            'nurse_name' => 'Suster Amira',
            'initial_volume' => 500,
            'installed_at' => '2026-05-20 12:00:00',
        ]);
        $oldMonitoring = $patient->infusionMonitorings()->create([
            'node_id' => 2,
            'bed_number' => 2,
            'unit_infus' => 'Kasur 2',
            'capacity_ml' => 500,
            'started_at' => '2026-05-20 12:00:00',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->post(route('patients.replace-infusion', $patient), [
            'replacement_volume' => 1000,
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
            'bed_number' => 2,
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
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        $oldPatient = Patient::create([
            'patient_name' => 'Feature Pasien Lama',
            'room_name' => 'Ruang Lama',
            'bed_number' => 1,
            'doctor_name' => 'dr. Lama',
            'nurse_name' => 'Suster Lama',
            'initial_volume' => 500,
            'installed_at' => '2026-05-20 08:00:00',
        ]);
        $oldMonitoring = $oldPatient->infusionMonitorings()->create([
            'node_id' => 1,
            'bed_number' => 1,
            'unit_infus' => 'Kasur 1',
            'capacity_ml' => 500,
            'started_at' => '2026-05-20 08:00:00',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->post('/patients', [
            'patient_name' => 'Feature Pasien Baru',
            'room_name' => 'Ruang Baru',
            'bed_number' => 1,
            'confirm_bed_transfer' => 1,
            'doctor_name' => 'dr. Baru',
            'nurse_name' => 'Suster Baru',
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
            'node_id' => 1,
            'bed_number' => 1,
            'status' => 'aktif',
        ]);
    }

    public function test_storing_patient_requires_confirmation_when_bed_is_occupied(): void
    {
        $user = User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'password' => Hash::make('password'),
            ],
        );

        $oldPatient = Patient::create([
            'patient_name' => 'Feature Pasien Aktif',
            'room_name' => 'Ruang Aktif',
            'bed_number' => 1,
            'doctor_name' => 'dr. Aktif',
            'nurse_name' => 'Suster Aktif',
            'initial_volume' => 500,
            'installed_at' => '2026-05-20 08:00:00',
        ]);
        $oldPatient->infusionMonitorings()->create([
            'node_id' => 1,
            'bed_number' => 1,
            'unit_infus' => 'Kasur 1',
            'capacity_ml' => 500,
            'started_at' => '2026-05-20 08:00:00',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->from('/patients/create')->post('/patients', [
            'patient_name' => 'Feature Pasien Baru Tanpa Konfirmasi',
            'room_name' => 'Ruang Baru',
            'bed_number' => 1,
            'doctor_name' => 'dr. Baru',
            'nurse_name' => 'Suster Baru',
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
}
