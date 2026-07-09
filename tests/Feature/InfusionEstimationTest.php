<?php

namespace Tests\Feature;

use App\Models\InfusionMonitoring;
use App\Models\InfusionReading;
use App\Models\Patient;
use App\Services\InfusionCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class InfusionEstimationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_estimation_uses_weight_trend_when_drop_is_stable(): void
    {
        config([
            'infusion.estimation_window_minutes' => 3,
            'infusion.estimation_min_samples' => 4,
            'infusion.estimation_min_drop_ml' => 2,
            'infusion.estimation_min_rate_ml_per_minute' => 0.2,
            'infusion.estimation_max_minutes' => 1440,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-07-09 10:00:00', config('app.timezone')));
        $monitoring = $this->createMonitoring();

        $this->createReading($monitoring, 500, now()->subMinutes(3));
        $this->createReading($monitoring, 490, now()->subMinutes(2));
        $this->createReading($monitoring, 480, now()->subMinute());
        $latest = $this->createReading($monitoring, 470, now());

        $estimate = app(InfusionCalculator::class)->timeRemainingFromWeightTrend($monitoring, $latest);

        $this->assertSame('00:47:00', $estimate);
    }

    public function test_estimation_waits_when_weight_drop_is_too_small(): void
    {
        config([
            'infusion.estimation_window_minutes' => 3,
            'infusion.estimation_min_samples' => 4,
            'infusion.estimation_min_drop_ml' => 2,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-07-09 10:00:00', config('app.timezone')));
        $monitoring = $this->createMonitoring();

        $this->createReading($monitoring, 500, now()->subMinutes(3));
        $this->createReading($monitoring, 499.8, now()->subMinutes(2));
        $this->createReading($monitoring, 499.6, now()->subMinute());
        $latest = $this->createReading($monitoring, 499.5, now());

        $estimate = app(InfusionCalculator::class)->timeRemainingFromWeightTrend($monitoring, $latest);

        $this->assertSame('Menunggu aliran', $estimate);
    }

    private function createMonitoring(): InfusionMonitoring
    {
        $patient = Patient::create([
            'patient_name' => 'Pasien Estimasi',
            'room_name' => 'Ruang 1',
            'bed_number' => 1,
            'doctor_name' => 'Dokter',
            'nurse_name' => 'Perawat',
            'initial_volume' => 500,
            'installed_at' => now(),
        ]);

        return InfusionMonitoring::create([
            'patient_id' => $patient->id,
            'node_id' => 1,
            'bed_number' => 1,
            'unit_infus' => 'Kasur 1',
            'capacity_ml' => 500,
            'started_at' => now(),
            'status' => 'aktif',
        ]);
    }

    private function createReading(InfusionMonitoring $monitoring, float $weight, Carbon $loggedAt): InfusionReading
    {
        return $monitoring->readings()->create([
            'node_id' => 1,
            'unit_infus' => 'Kasur 1',
            'logged_at' => $loggedAt,
            'weight' => $weight,
            'drip_rate_tpm' => 0,
            'remaining_percentage' => ($weight / 500) * 100,
            'device_status' => 'normal',
            'payload' => ['berat' => $weight],
        ]);
    }
}
