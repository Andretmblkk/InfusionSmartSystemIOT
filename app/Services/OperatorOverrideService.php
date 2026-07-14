<?php

namespace App\Services;

use App\Models\InfusionMonitoring;
use App\Models\OperatorOverride;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class OperatorOverrideService
{
    public function activeForNode(int $nodeId): ?OperatorOverride
    {
        return OperatorOverride::query()
            ->where('node_id', $nodeId)
            ->where('active', true)
            ->first();
    }

    public function applyCondition(
        User $user,
        int $bedNumber,
        int $nodeId,
        string $condition,
        ?InfusionMonitoring $monitoring = null,
    ): OperatorOverride {
        $override = $this->existingOverride($nodeId);
        $baseline = $this->currentBaseline($override, $monitoring, $condition === 'normal');
        $now = now();

        $baselinePercentage = $baseline['percentage'];
        $baselineWeight = $baseline['weight'];
        $flowProfile = $override?->flow_profile ?: 'pending';
        $hasFlowStarted = (bool) ($override?->has_flow_started ?? false);

        if ($condition === 'empty') {
            $baselinePercentage = 0;
            $baselineWeight = 0;
            $flowProfile = 'stopped';
            $hasFlowStarted = true;
        }

        return OperatorOverride::query()->updateOrCreate(
            ['node_id' => $nodeId],
            [
                'bed_number' => $bedNumber,
                'operator_user_id' => $user->id,
                'active' => true,
                'condition' => $condition,
                'flow_profile' => $flowProfile,
                'has_flow_started' => $hasFlowStarted,
                'baseline_weight' => $baselineWeight,
                'baseline_percentage' => $baselinePercentage,
                'baseline_recorded_at' => $now,
                'released_at' => null,
            ],
        );
    }

    public function applyFlowProfile(
        User $user,
        int $bedNumber,
        int $nodeId,
        string $flowProfile,
        ?InfusionMonitoring $monitoring = null,
    ): OperatorOverride {
        $override = $this->existingOverride($nodeId);
        $baseline = $this->currentBaseline($override, $monitoring, true);
        $now = now();

        $hasFlowStarted = $override?->has_flow_started ?? false;

        if (in_array($flowProfile, ['slow', 'medium', 'fast'], true)) {
            $hasFlowStarted = true;
        }

        if ($flowProfile === 'pending') {
            $hasFlowStarted = false;
        }

        if ($flowProfile === 'stopped' && ! $hasFlowStarted) {
            $hasFlowStarted = true;
        }

        return OperatorOverride::query()->updateOrCreate(
            ['node_id' => $nodeId],
            [
                'bed_number' => $bedNumber,
                'operator_user_id' => $user->id,
                'active' => true,
                'condition' => 'normal',
                'flow_profile' => $flowProfile,
                'has_flow_started' => $hasFlowStarted,
                'baseline_weight' => $baseline['weight'],
                'baseline_percentage' => $baseline['percentage'],
                'baseline_recorded_at' => $now,
                'released_at' => null,
            ],
        );
    }

    public function release(User $user, int $nodeId): void
    {
        OperatorOverride::query()
            ->where('node_id', $nodeId)
            ->update([
                'operator_user_id' => $user->id,
                'active' => false,
                'released_at' => now(),
            ]);
    }

    public function snapshotForMonitoring(InfusionMonitoring $monitoring, Patient $patient): ?array
    {
        $override = $this->activeForNode((int) $monitoring->node_id);

        if (! $override) {
            return null;
        }

        $capacity = max(1, (int) $monitoring->capacity_ml);
        $baselineAt = $override->baseline_recorded_at ?? $override->updated_at ?? now();
        $elapsedMinutes = max(0, $baselineAt->diffInSeconds(now()) / 60);
        $currentWeight = $override->baseline_weight;
        $currentPercentage = $override->baseline_percentage;
        $statusLabel = 'Normal';
        $cardStatus = 'normal';
        $statusTone = 'green';
        $progressTone = 'green';
        $weightTone = 'green';
        $timeRemaining = '-';
        $isOffline = false;

        if ($override->condition === 'offline') {
            $statusLabel = 'Offline';
            $cardStatus = 'warning';
            $statusTone = 'red';
            $progressTone = 'yellow';
            $weightTone = 'red';
            $isOffline = true;
        } elseif ($override->condition === 'empty') {
            $currentWeight = 0;
            $currentPercentage = 0;
            $statusLabel = 'Ganti';
            $cardStatus = 'critical';
            $statusTone = 'cyan';
            $progressTone = 'red';
            $weightTone = 'red';
        } else {
            $profile = $this->profile($override->flow_profile);
            $ratePerMinute = $profile['rate_ml_per_hour'] / 60;

            if ($ratePerMinute > 0) {
                $currentWeight = max(0, $override->baseline_weight - ($ratePerMinute * $elapsedMinutes));
                $currentPercentage = max(0, min(100, ($currentWeight / $capacity) * 100));
                $timeRemaining = $this->timeRemainingFromRate($currentWeight, $ratePerMinute);

                if ($currentPercentage <= app(InfusionCalculator::class)->emptyPercentage()) {
                    $statusLabel = 'Ganti';
                    $cardStatus = 'critical';
                    $statusTone = 'cyan';
                    $progressTone = 'red';
                    $weightTone = 'red';
                } elseif ($currentPercentage <= app(InfusionCalculator::class)->lowPercentage()) {
                    $statusLabel = 'Peringatan';
                    $cardStatus = 'warning';
                    $statusTone = 'red';
                    $progressTone = 'yellow';
                    $weightTone = 'red';
                } else {
                    $statusLabel = 'Normal';
                    $cardStatus = 'normal';
                    $statusTone = 'green';
                    $progressTone = 'green';
                    $weightTone = 'green';
                }
            } elseif ($override->flow_profile === 'pending' && ! $override->has_flow_started) {
                $statusLabel = 'Normal';
                $cardStatus = 'normal';
                $statusTone = 'green';
                $progressTone = 'green';
                $weightTone = 'green';
                $timeRemaining = 'Menunggu aliran';
            } elseif ($override->flow_profile === 'stopped' && $override->has_flow_started) {
                $statusLabel = $elapsedMinutes >= max(1, (int) config('infusion.stagnation_minutes', 1)) ? 'Macet' : 'Menunggu';
                $cardStatus = $statusLabel === 'Macet' ? 'warning' : 'warning';
                $statusTone = 'red';
                $progressTone = 'yellow';
                $weightTone = 'green';
                $timeRemaining = '-';
            }
        }

        return [
            'percentage' => (int) round($currentPercentage),
            'currentWeight' => (int) round($currentWeight),
            'timeRemaining' => $timeRemaining,
            'cardStatus' => $cardStatus,
            'statusLabel' => $statusLabel,
            'statusTone' => $statusTone,
            'weightTone' => $weightTone,
            'progressTone' => $progressTone,
            'isOffline' => $isOffline,
            'hasReading' => true,
            'monitoring' => $monitoring,
        ];
    }

    public function panelCards(Collection $monitorings, Collection $overrides): array
    {
        $cards = [];
        $display = app(InfusionDisplayService::class);
        $beds = config('infusion.beds', []);

        foreach ($beds as $bedNumber => $bed) {
            $nodeId = (int) $bed['node_id'];
            $monitoring = $monitorings->firstWhere('node_id', $nodeId);
            $override = $overrides->firstWhere('node_id', $nodeId);
            $patient = $monitoring?->patient;
            $reading = $monitoring?->latestReading;
            $hardwareOnline = $reading && $reading->logged_at->gte(now()->subSeconds(max(5, (int) config('infusion.offline_seconds', 30))));
            $displaySnapshot = $patient ? $display->patientDetail($patient) : null;

            $cards[] = [
                'bedNumber' => (int) $bedNumber,
                'bedLabel' => $bed['label'] ?? 'Bed ' . $bedNumber,
                'nodeId' => $nodeId,
                'patientName' => $patient?->patient_name,
                'roomName' => $patient?->room_name,
                'displayStatus' => $displaySnapshot['status'] ?? 'Belum aktif',
                'displayPercentage' => $displaySnapshot['percentage'] ?? 0,
                'displayWeight' => $displaySnapshot['currentWeight'] ?? 0,
                'sourceLabel' => $override?->active ? 'Override Operator' : 'Data Alat',
                'overrideActive' => (bool) ($override?->active ?? false),
                'condition' => $override?->condition ?? 'normal',
                'conditionLabel' => $this->conditionLabel($override?->condition ?? 'normal'),
                'flowProfile' => $override?->flow_profile ?? 'pending',
                'flowProfileLabel' => $this->profile($override?->flow_profile ?? 'pending')['label'],
                'hardwareOnline' => (bool) $hardwareOnline,
                'hardwareLabel' => $this->hardwareLabel($reading, $hardwareOnline),
                'lastReadingLabel' => $reading?->logged_at
                    ? $reading->logged_at->locale('id')->diffForHumans(now(), ['parts' => 2, 'short' => false, 'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW])
                    : 'belum pernah kirim',
                'realWeight' => $reading ? (int) round($reading->weight) : null,
                'realPercentage' => $reading ? (int) round($reading->remaining_percentage) : null,
                'recoveredWhileOverride' => (bool) ($override?->active && $hardwareOnline),
                'canControl' => (bool) $monitoring,
            ];
        }

        return $cards;
    }

    public function flowProfiles(): array
    {
        return config('infusion.operator_profiles', []);
    }

    private function existingOverride(int $nodeId): ?OperatorOverride
    {
        return OperatorOverride::query()->where('node_id', $nodeId)->first();
    }

    private function currentBaseline(?OperatorOverride $override, ?InfusionMonitoring $monitoring, bool $preferRealBaseline = false): array
    {
        if ($override && $override->active && ! $preferRealBaseline && ! in_array($override->condition, ['offline', 'empty'], true)) {
            $snapshot = $this->snapshotForMonitoring(
                $monitoring ?? new InfusionMonitoring([
                    'node_id' => $override->node_id,
                    'capacity_ml' => max(1, (int) round(($override->baseline_weight * 100) / max(1, $override->baseline_percentage ?: 100))),
                ]),
                $monitoring?->patient ?? new Patient([
                    'initial_volume' => max(1, (int) round(($override->baseline_weight * 100) / max(1, $override->baseline_percentage ?: 100))),
                ]),
            );

            if ($snapshot) {
                return [
                    'weight' => (float) $snapshot['currentWeight'],
                    'percentage' => (float) $snapshot['percentage'],
                ];
            }
        }

        $reading = $monitoring?->latestReading;
        $capacity = max(1, (int) ($monitoring?->capacity_ml ?: $monitoring?->patient?->initial_volume ?: 500));

        if ($reading) {
            return [
                'weight' => (float) $reading->weight,
                'percentage' => (float) $reading->remaining_percentage,
            ];
        }

        return [
            'weight' => (float) $capacity,
            'percentage' => 100.0,
        ];
    }

    private function profile(string $flowProfile): array
    {
        return $this->flowProfiles()[$flowProfile] ?? $this->flowProfiles()['pending'];
    }

    private function timeRemainingFromRate(float $remainingWeight, float $ratePerMinute): string
    {
        if ($ratePerMinute <= 0 || $remainingWeight <= 0) {
            return '-';
        }

        $totalMinutes = (int) ceil($remainingWeight / $ratePerMinute);

        if ($totalMinutes <= 0) {
            return '00:00:00';
        }

        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d:00', $hours, $minutes);
    }

    private function hardwareLabel($reading, bool $hardwareOnline): string
    {
        if (! $reading) {
            return 'Alat belum terhubung';
        }

        return $hardwareOnline ? 'Alat terhubung' : 'Alat tidak aktif';
    }

    private function conditionLabel(string $condition): string
    {
        return match ($condition) {
            'offline' => 'Offline',
            'empty' => 'Habis',
            default => 'Normal',
        };
    }
}
