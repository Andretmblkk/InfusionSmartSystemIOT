<?php

namespace App\Services;

use App\Models\InfusionMonitoring;
use App\Models\InfusionReading;
use Illuminate\Support\Carbon;

class InfusionCalculator
{
    public function percentage(?float $reportedPercentage, float $weight, int $capacityMl): float
    {
        if ($reportedPercentage !== null) {
            return $this->clampPercentage($reportedPercentage);
        }

        if ($capacityMl <= 0) {
            return 0;
        }

        return $this->clampPercentage(($weight / $capacityMl) * 100);
    }

    public function deviceStatus(
        InfusionMonitoring $monitoring,
        float $weight,
        Carbon $loggedAt,
        ?string $reportedStatus = null,
        ?float $remainingPercentage = null,
    ): string
    {
        $reported = $this->normalizeReportedStatus($reportedStatus);
        $percentage = $remainingPercentage ?? $this->percentage(null, $weight, $monitoring->capacity_ml);

        if ($reported === 'habis' || $percentage <= $this->emptyPercentage()) {
            return 'habis';
        }

        if ($reported === 'macet' && $this->canDetectStagnation($percentage)) {
            return 'macet';
        }

        if ($this->canDetectStagnation($percentage) && $this->isWeightStagnant($monitoring, $weight, $loggedAt)) {
            return 'macet';
        }

        if ($reported === 'warning' || $percentage <= $this->lowPercentage()) {
            return 'warning';
        }

        return 'normal';
    }

    public function normalizeReportedStatus(?string $status): string
    {
        $normalized = strtolower(trim((string) ($status ?: 'normal')));

        return match ($normalized) {
            'habis' => 'habis',
            'empty' => 'habis',
            'ganti' => 'habis',
            'macet' => 'macet',
            'blocked' => 'macet',
            'stagnan' => 'macet',
            'low' => 'warning',
            'warning' => 'warning',
            'peringatan' => 'warning',
            'hampir habis' => 'warning',
            default => 'normal',
        };
    }

    public function isWeightStagnant(InfusionMonitoring $monitoring, float $weight, Carbon $loggedAt): bool
    {
        $minutes = max(1, (int) config('infusion.stagnation_minutes', 5));
        $tolerance = max(0, (float) config('infusion.stagnation_tolerance_grams', 1));
        $cutoff = $loggedAt->copy()->subMinutes($minutes);

        return $monitoring->readings()
            ->where('logged_at', '<=', $cutoff)
            ->whereBetween('weight', [$weight - $tolerance, $weight + $tolerance])
            ->exists();
    }

    private function canDetectStagnation(float $percentage): bool
    {
        $startBelow = max(0, min(100, (float) config('infusion.stagnation_start_below_percentage', 98)));

        return $percentage < $startBelow;
    }

    public function monitoringStatus(string $deviceStatus): string
    {
        return $deviceStatus === 'normal' ? 'aktif' : 'bermasalah';
    }

    public function lowPercentage(): float
    {
        return max(0, (float) config('infusion.low_percentage', 10));
    }

    public function emptyPercentage(): float
    {
        return max(0, (float) config('infusion.empty_percentage', 5));
    }

    public function timeRemaining(float $remainingPercentage, float $dripRateTpm, int $capacityMl): string
    {
        if ($dripRateTpm <= 0 || $remainingPercentage <= 0) {
            return '-';
        }

        $remainingMl = ($capacityMl * $remainingPercentage) / 100;
        $totalMinutes = (int) round(($remainingMl * 20) / $dripRateTpm);
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d:00', $hours, $minutes);
    }

    public function timeRemainingFromWeightTrend(InfusionMonitoring $monitoring, InfusionReading $reading): string
    {
        if ($reading->weight <= 0) {
            return '00:00:00';
        }

        if ($reading->remaining_percentage >= (float) config('infusion.stagnation_start_below_percentage', 98)) {
            return 'Menunggu aliran';
        }

        $windowMinutes = max(1, (int) config('infusion.estimation_window_minutes', 3));
        $minimumSamples = max(2, (int) config('infusion.estimation_min_samples', 4));
        $minimumDrop = max(0.1, (float) config('infusion.estimation_min_drop_ml', 2));
        $minimumRate = max(0.01, (float) config('infusion.estimation_min_rate_ml_per_minute', 0.2));
        $maximumMinutes = max(1, (int) config('infusion.estimation_max_minutes', 24 * 60));
        $cutoff = $reading->logged_at->copy()->subMinutes($windowMinutes);

        $readings = $monitoring->readings()
            ->where('logged_at', '>=', $cutoff)
            ->where('logged_at', '<=', $reading->logged_at)
            ->orderBy('logged_at')
            ->get(['logged_at', 'weight']);

        if ($readings->count() < $minimumSamples) {
            return 'Menunggu data';
        }

        $first = $readings->first();
        $last = $readings->last();
        $elapsedMinutes = $first->logged_at->diffInSeconds($last->logged_at) / 60;

        if ($elapsedMinutes < 1) {
            return 'Menunggu data';
        }

        $netDrop = $first->weight - $last->weight;

        if ($netDrop < $minimumDrop) {
            return 'Menunggu aliran';
        }

        $slope = $this->weightSlopePerMinute($readings);

        if ($slope >= 0) {
            return 'Menunggu aliran';
        }

        $mlPerMinute = abs($slope);

        if ($mlPerMinute < $minimumRate) {
            return 'Menunggu aliran';
        }

        $totalMinutes = (int) ceil($reading->weight / $mlPerMinute);

        if ($totalMinutes <= 0 || $totalMinutes > $maximumMinutes) {
            return 'Menghitung';
        }

        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d:00', $hours, $minutes);
    }

    private function weightSlopePerMinute($readings): float
    {
        $firstTime = $readings->first()->logged_at;
        $count = $readings->count();
        $sumX = 0.0;
        $sumY = 0.0;
        $sumXY = 0.0;
        $sumXX = 0.0;

        foreach ($readings as $reading) {
            $x = $firstTime->diffInSeconds($reading->logged_at) / 60;
            $y = (float) $reading->weight;

            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumXX += $x * $x;
        }

        $denominator = ($count * $sumXX) - ($sumX * $sumX);

        if (abs($denominator) < 0.000001) {
            return 0.0;
        }

        return (($count * $sumXY) - ($sumX * $sumY)) / $denominator;
    }

    private function clampPercentage(float $percentage): float
    {
        return round(max(0, min(100, $percentage)), 2);
    }
}
