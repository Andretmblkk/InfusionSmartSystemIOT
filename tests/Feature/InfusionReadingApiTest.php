<?php

namespace Tests\Feature;

use App\Models\InfusionMonitoring;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class InfusionReadingApiTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_legacy_endpoint_accepts_json_payload(): void
    {
        $monitoring = $this->createMonitoring(1);

        $response = $this->postJson('/api/api.php', [
            'node' => 1,
            'berat' => 343.6,
            'volume' => 68.7,
            'laju' => 25,
            'status_infus' => 'normal',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data Berhasil',
                'node' => 1,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 1,
            'device_status' => 'normal',
        ]);
    }

    public function test_root_legacy_endpoint_accepts_old_firmware_payload(): void
    {
        $monitoring = $this->createMonitoring(1);

        $response = $this->postJson('/api.php', [
            'node' => 1,
            'berat' => 275,
            'persen' => 55,
            'tpm' => 18,
            'status' => 'normal',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data Berhasil',
                'node' => 1,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 1,
            'remaining_percentage' => 55,
            'device_status' => 'normal',
        ]);
    }

    public function test_legacy_endpoint_ignores_blocked_drip_sensor_without_stagnant_weight(): void
    {
        $monitoring = $this->createMonitoring(2);

        $response = $this->post('/api/api.php', [
            'node' => 2,
            'berat' => 341,
            'persen' => 68.2,
            'tpm' => 0,
            'status_tetesan' => 'terhambat',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'node' => 2,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 2,
            'device_status' => 'normal',
        ]);

        $this->assertDatabaseHas('infusion_monitorings', [
            'id' => $monitoring->id,
            'status' => 'aktif',
        ]);
    }

    public function test_legacy_endpoint_marks_stagnant_weight_as_blocked(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-06-27 10:00:00', config('app.timezone')));

        $monitoring = $this->createMonitoring(2);
        $monitoring->readings()->create([
            'node_id' => 2,
            'unit_infus' => 'Infus 2',
            'logged_at' => now()->subMinutes(6),
            'weight' => 341,
            'drip_rate_tpm' => 20,
            'remaining_percentage' => 68.2,
            'device_status' => 'normal',
            'payload' => ['node' => 2, 'berat' => 341],
        ]);

        $response = $this->postJson('/api/api.php', [
            'node' => 2,
            'berat' => 341.5,
            'persen' => 68.2,
            'tpm' => 20,
            'status_infus' => 'normal',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'node' => 2,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 2,
            'device_status' => 'macet',
        ]);

        $this->assertDatabaseHas('infusion_monitorings', [
            'id' => $monitoring->id,
            'status' => 'bermasalah',
        ]);
    }

    public function test_legacy_endpoint_marks_low_fluid_as_warning(): void
    {
        $monitoring = $this->createMonitoring(1);

        $response = $this->postJson('/api/api.php', [
            'node' => 1,
            'berat' => 40,
            'persen' => 8,
            'tpm' => 0,
            'status_infus' => 'LOW',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'node' => 1,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 1,
            'remaining_percentage' => 8,
            'device_status' => 'warning',
        ]);

        $this->assertDatabaseHas('infusion_monitorings', [
            'id' => $monitoring->id,
            'status' => 'bermasalah',
        ]);
    }

    public function test_legacy_endpoint_marks_five_percent_as_empty_even_without_reported_status(): void
    {
        $monitoring = $this->createMonitoring(1);

        $response = $this->postJson('/api/api.php', [
            'node' => 1,
            'berat' => 25,
            'persen' => 5,
            'tpm' => 0,
            'status_infus' => 'normal',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'node' => 1,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 1,
            'remaining_percentage' => 5,
            'device_status' => 'habis',
        ]);
    }

    public function test_legacy_endpoint_marks_empty_bag_as_problematic(): void
    {
        $monitoring = $this->createMonitoring(3);

        $response = $this->postJson('/api/api.php', [
            'node' => 3,
            'berat' => 0,
            'volume' => 0,
            'laju' => 0,
            'status_infus' => 'habis',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'node' => 3,
            ]);

        $this->assertDatabaseHas('infusion_readings', [
            'infusion_monitoring_id' => $monitoring->id,
            'node_id' => 3,
            'remaining_percentage' => 0,
            'device_status' => 'habis',
        ]);

        $this->assertDatabaseHas('infusion_monitorings', [
            'id' => $monitoring->id,
            'status' => 'bermasalah',
        ]);
    }

    public function test_legacy_endpoint_rejects_invalid_node(): void
    {
        $response = $this->postJson('/api/api.php', [
            'node' => 4,
            'berat' => 100,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => false,
                'message' => 'Node ID invalid (1-3)',
            ]);
    }

    public function test_legacy_endpoint_reports_when_no_active_monitoring_exists(): void
    {
        $response = $this->postJson('/api/api.php', [
            'node' => 1,
            'berat' => 100,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => false,
                'message' => 'No Active Monitoring for Node 1',
            ]);
    }

    private function createMonitoring(int $node): InfusionMonitoring
    {
        $patient = Patient::create([
            'patient_name' => 'Pasien Node ' . $node,
            'room_name' => 'Ruang ' . $node,
            'bed_number' => $node,
            'doctor_name' => 'Dokter',
            'nurse_name' => 'Perawat',
            'initial_volume' => 500,
            'installed_at' => now(),
        ]);

        return InfusionMonitoring::create([
            'patient_id' => $patient->id,
            'node_id' => $node,
            'bed_number' => $node,
            'unit_infus' => 'Kasur ' . $node,
            'capacity_ml' => 500,
            'started_at' => now(),
            'status' => 'aktif',
        ]);
    }
}
