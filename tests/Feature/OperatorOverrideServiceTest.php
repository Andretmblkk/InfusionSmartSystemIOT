<?php

namespace Tests\Feature;

use App\Models\InfusionMonitoring;
use App\Models\Patient;
use App\Models\User;
use App\Services\OperatorOverrideService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatorOverrideServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_flow_override_closes_empty_sensor_reading(): void
    {
        $user = $this->operatorUser();
        $monitoring = $this->monitoringWithEmptyReading();
        $service = app(OperatorOverrideService::class);

        $service->applyFlowProfile($user, 1, 2, 'slow', $monitoring);

        $snapshot = $service->snapshotForMonitoring($monitoring, $monitoring->patient);

        $this->assertSame('Normal', $snapshot['statusLabel']);
        $this->assertSame(100, $snapshot['percentage']);
        $this->assertSame(500, $snapshot['currentWeight']);
    }

    public function test_normal_override_closes_empty_sensor_reading(): void
    {
        $user = $this->operatorUser();
        $monitoring = $this->monitoringWithEmptyReading();
        $service = app(OperatorOverrideService::class);

        $service->applyCondition($user, 1, 2, 'normal', $monitoring);

        $snapshot = $service->snapshotForMonitoring($monitoring, $monitoring->patient);

        $this->assertSame('Normal', $snapshot['statusLabel']);
        $this->assertSame(100, $snapshot['percentage']);
        $this->assertSame(500, $snapshot['currentWeight']);
    }

    public function test_override_does_not_attach_to_new_monitoring_session_on_same_node(): void
    {
        $user = $this->operatorUser();
        $oldMonitoring = $this->monitoringWithEmptyReading();
        $service = app(OperatorOverrideService::class);

        $service->applyFlowProfile($user, 1, 2, 'slow', $oldMonitoring);

        $newPatient = Patient::create([
            'patient_name' => 'Pasien Baru',
            'room_name' => 'VIP Dewasa',
            'bed_number' => 1,
            'doctor_name' => 'Dokter',
            'nurse_name' => 'Perawat',
            'initial_volume' => 500,
            'installed_at' => now(),
        ]);

        $newMonitoring = InfusionMonitoring::create([
            'patient_id' => $newPatient->id,
            'node_id' => 2,
            'bed_number' => 1,
            'unit_infus' => 'Bed 1',
            'capacity_ml' => 500,
            'started_at' => now(),
            'status' => 'aktif',
        ]);

        $this->assertNull($service->snapshotForMonitoring($newMonitoring, $newPatient));
    }

    private function operatorUser(): User
    {
        return User::create([
            'name' => 'Operator Demo',
            'employee_id' => '220803',
            'is_operator' => true,
            'email' => 'operator-demo@example.test',
            'password' => 'operator123',
        ]);
    }

    private function monitoringWithEmptyReading(): InfusionMonitoring
    {
        $patient = Patient::create([
            'patient_name' => 'Petrus Rumbiak',
            'room_name' => 'VIP Dewasa',
            'bed_number' => 1,
            'doctor_name' => 'dr. Anita Rumainum, Sp.JP',
            'nurse_name' => 'Suster Amira',
            'initial_volume' => 500,
            'installed_at' => now(),
        ]);

        $monitoring = InfusionMonitoring::create([
            'patient_id' => $patient->id,
            'node_id' => 2,
            'bed_number' => 1,
            'unit_infus' => 'Bed 1',
            'capacity_ml' => 500,
            'started_at' => now(),
            'status' => 'bermasalah',
        ]);

        $monitoring->readings()->create([
            'node_id' => 2,
            'unit_infus' => 'Bed 1',
            'logged_at' => now(),
            'weight' => 0,
            'drip_rate_tpm' => 0,
            'remaining_percentage' => 0,
            'device_status' => 'habis',
            'payload' => ['node' => 2, 'berat' => 0, 'persen' => 0],
        ]);

        return $monitoring->load(['patient', 'latestReading']);
    }
}
