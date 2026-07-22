<?php

namespace App\Services;

use App\Models\InfusionReading;
use App\Models\InfusionMonitoring;
use App\Models\Patient;
use Illuminate\Support\Collection;

class InfusionDisplayService
{
    public function dashboardRows(Collection $patients): array
    {
        return $patients->take(4)->values()->map(function (Patient $patient, int $index): array {
            $snapshot = $this->snapshot($patient, $index);

            return [
                'initials' => $this->initials($patient->patient_name),
                'name' => $patient->patient_name,
                'meta' => 'ID: ' . str_pad((string) $patient->id, 6, '0', STR_PAD_LEFT) . ' - ' . $patient->nurse_name,
                'room' => $this->locationLabel($patient, $snapshot['monitoring']),
                'status' => $snapshot['statusLabel'],
                'statusTone' => $snapshot['statusTone'],
                'currentWeight' => $snapshot['currentWeight'],
                'emptyWeight' => 50,
                'fullWeight' => $patient->initial_volume + 50,
                'weightTone' => $snapshot['weightTone'],
                'avatarTone' => ['blue', 'green', 'teal'][$index % 3],
                'href' => route('patients.show', $patient),
                'timeRemaining' => $snapshot['timeRemaining'],
            ];
        })->all();
    }

    public function monitoringCards(Collection $patients): array
    {
        return $patients->take(6)->values()->map(function (Patient $patient, int $index): array {
            $snapshot = $this->snapshot($patient, $index);

            return [
                'status' => $snapshot['cardStatus'],
                'statusLabel' => $snapshot['statusLabel'],
                'name' => $patient->patient_name,
                'room' => $this->locationLabel($patient, $snapshot['monitoring']),
                'doctor' => $patient->doctor_name,
                'initialVolume' => $patient->initial_volume . ' ml',
                'currentWeight' => $snapshot['currentWeight'] . ' gram',
                'timeRemaining' => $snapshot['timeRemaining'],
                'progress' => $snapshot['percentage'],
                'progressTone' => $snapshot['progressTone'],
                'href' => route('patients.show', $patient),
            ];
        })->all();
    }

    public function patientDetail(Patient $patient): array
    {
        $snapshot = $this->snapshot($patient, 0);

        return [
            'initials' => $this->initials($patient->patient_name),
            'status' => $snapshot['statusLabel'],
            'statusTone' => $snapshot['statusTone'],
            'cardStatus' => $snapshot['cardStatus'],
            'percentage' => $snapshot['percentage'],
            'currentWeight' => $snapshot['currentWeight'],
            'timeRemaining' => $snapshot['timeRemaining'],
            'progressTone' => $snapshot['progressTone'],
            'emptyWeight' => 50,
            'fullWeight' => $patient->initial_volume + 50,
            'location' => $this->locationLabel($patient, $snapshot['monitoring']),
            'bedLabel' => $this->monitoringBedLabel($snapshot['monitoring']) ?? $this->bedLabel($patient->bed_number),
            'nodeId' => $snapshot['monitoring']?->node_id,
            'monitoring' => $snapshot['monitoring'],
            'infusionName' => $snapshot['monitoring'] ? $this->infusionName($snapshot['monitoring']) : '-',
            'responsibleNurse' => $snapshot['monitoring']?->responsible_nurse ?? $patient->nurse_name,
            'infusionSessions' => $this->infusionSessions($patient),
        ];
    }

    public function alerts(Collection $patients): array
    {
        return $patients->values()
            ->map(function (Patient $patient, int $index): ?array {
                $snapshot = $this->snapshot($patient, $index);

                if (! $snapshot['hasReading'] || $snapshot['cardStatus'] === 'normal' || $snapshot['statusLabel'] === 'Menunggu') {
                    return null;
                }

                return [
                    'status' => $snapshot['cardStatus'],
                    'label' => $snapshot['statusLabel'],
                    'patient' => $patient->patient_name,
                    'room' => $this->locationLabel($patient, $snapshot['monitoring']),
                    'percentage' => $snapshot['percentage'],
                    'message' => $this->alertMessage($snapshot['statusLabel'], $patient, $snapshot['percentage']),
                    'href' => route('patients.show', $patient),
                ];
            })
            ->filter()
            ->take(4)
            ->values()
            ->all();
    }

    public function stats(Collection $patients): array
    {
        $snapshots = $patients->values()->map(fn (Patient $patient, int $index): array => $this->snapshot($patient, $index));

        return [
            'total' => $patients->count(),
            'active' => $snapshots->where('isOffline', false)->count(),
            'critical' => $snapshots->where('cardStatus', 'critical')->count(),
            'normal' => $snapshots->where('cardStatus', 'normal')->count(),
        ];
    }

    public function recentHistory(Collection $patients): array
    {
        $patients->loadMissing('infusionMonitorings.latestReading');

        return $patients
            ->filter(fn (Patient $patient): bool => $patient->infusionMonitorings->isNotEmpty())
            ->sortByDesc(function (Patient $patient) {
                return optional($patient->infusionMonitorings->max('started_at'))?->timestamp ?? 0;
            })
            ->values()
            ->map(fn (Patient $patient, int $index): array => $this->buildReportRow($patient, $index))
            ->take(12)
            ->all();
    }

    public function dashboardActivityPanel(Collection $patients): array
    {
        $patients->loadMissing('latestInfusionMonitoring.latestReading');

        $snapshots = $patients
            ->values()
            ->map(function (Patient $patient, int $index): array {
                $snapshot = $this->snapshot($patient, $index);
                $monitoring = $snapshot['monitoring'];
                $reading = $monitoring?->latestReading;

                return [
                    'patient' => $patient,
                    'snapshot' => $snapshot,
                    'loggedAt' => $reading?->logged_at,
                    'bedLabel' => $this->monitoringBedLabel($monitoring) ?? $this->bedLabel($patient->bed_number) ?? 'Bed',
                ];
            })
            ->sortByDesc(fn (array $item) => optional($item['loggedAt'])->timestamp ?? 0)
            ->values();

        $latestSnapshot = $snapshots->first(fn (array $item): bool => $item['loggedAt'] !== null);
        $latestLoggedAt = $latestSnapshot['loggedAt'] ?? null;

        return [
            'updatedLabel' => $latestLoggedAt
                ? 'Update ' . $latestLoggedAt->locale('id')->diffForHumans(now(), [
                    'parts' => 2,
                    'short' => false,
                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                ])
                : 'Belum ada pembacaan terbaru',
            'items' => $snapshots
                ->take(2)
                ->map(fn (array $item): array => $this->buildActivityItem(
                    $item['bedLabel'],
                    $item['snapshot']['statusLabel'],
                    (int) $item['snapshot']['percentage'],
                ))
                ->all(),
        ];
    }

    public function reportRow(Patient $patient): array
    {
        $patient->loadMissing(['registeredPatient', 'infusionMonitorings.latestReading', 'latestInfusionMonitoring.latestReading']);

        return $this->buildReportRow($patient, 0);
    }

    private function infusionSessions(Patient $patient): array
    {
        $patient->loadMissing('infusionMonitorings.latestReading');

        return $patient->infusionMonitorings
            ->sortByDesc('started_at')
            ->values()
            ->map(function (InfusionMonitoring $monitoring, int $index): array {
                $reading = $monitoring->latestReading;

                return [
                    'sequence' => $index + 1,
                    'infusionName' => $this->infusionName($monitoring),
                    'volume' => $monitoring->capacity_ml . ' ml',
                    'nurse' => $monitoring->responsible_nurse ?: '-',
                    'startedAt' => optional($monitoring->started_at)->format('d M Y, H:i') ?? '-',
                    'endedAt' => optional($monitoring->ended_at)->format('d M Y, H:i') ?? '-',
                    'status' => $this->monitoringStatusLabel($monitoring->status),
                    'latestPercentage' => $reading ? $this->formatNumber($reading->remaining_percentage) . '%' : '-',
                ];
            })
            ->all();
    }

    private function legacyHistoryFallback(Collection $patients): array
    {
        return $patients->take(8)->values()->map(function (Patient $patient, int $index): array {
            $snapshot = $this->snapshot($patient, $index);

            return [
                'time' => optional($patient->updated_at)->format('d M Y, H:i') ?? '-',
                'patient' => $patient->patient_name,
                'room' => $this->locationLabel($patient, $snapshot['monitoring']),
                'weight' => $snapshot['currentWeight'] . ' gram',
                'percentage' => $snapshot['percentage'] . '%',
                'status' => $snapshot['statusLabel'],
                'tone' => $snapshot['statusTone'],
            ];
        })->all();
    }

    private function buildReportRow(Patient $patient, int $index): array
    {
        $sessions = $patient->infusionMonitorings->sortBy('started_at')->values();
        $latestSession = $sessions->last();
        $replacementCount = max(0, $sessions->count() - 1);
        $infusionNames = $sessions
            ->map(fn (InfusionMonitoring $monitoring): string => $this->infusionName($monitoring))
            ->filter()
            ->unique()
            ->values();
        $nurses = $sessions
            ->pluck('responsible_nurse')
            ->filter()
            ->unique()
            ->values();

        return [
            'patient' => $patient->patient_name,
            'medicalRecord' => $patient->registeredPatient?->nomor_rekam_medis ?? '-',
            'room' => $this->locationLabel($patient, $latestSession),
            'bed' => $this->monitoringBedLabel($latestSession) ?? $this->bedLabel($patient->bed_number) ?? '-',
            'doctor' => $patient->doctor_name,
            'infusionCount' => $sessions->count(),
            'replacementCount' => $replacementCount,
            'infusionNames' => $infusionNames->isNotEmpty() ? $infusionNames->implode(', ') : '-',
            'latestInfusion' => $latestSession ? $this->infusionName($latestSession) : '-',
            'nurses' => $nurses->isNotEmpty() ? $nurses->implode(', ') : $patient->nurse_name,
            'firstDate' => optional($sessions->first()?->started_at)->format('d M Y, H:i') ?? '-',
            'latestDate' => optional($latestSession?->started_at)->format('d M Y, H:i') ?? '-',
            'status' => $this->monitoringStatusLabel($latestSession?->status),
            'tone' => $this->monitoringStatusTone($latestSession?->status),
            'href' => route('monitoring.history.show', $patient),
        ];
    }

    private function buildActivityItem(string $bedLabel, string $statusLabel, int $percentage): array
    {
        return match ($statusLabel) {
            'Offline' => [
                'label' => "{$bedLabel}: Node offline",
                'tone' => 'yellow',
            ],
            'Ganti' => [
                'label' => "{$bedLabel}: Sisa infus {$percentage}%",
                'tone' => 'red',
            ],
            'Macet' => [
                'label' => "{$bedLabel}: Aliran macet",
                'tone' => 'yellow',
            ],
            'Peringatan' => [
                'label' => "{$bedLabel}: Sisa infus {$percentage}%",
                'tone' => 'blue',
            ],
            default => [
                'label' => "{$bedLabel}: Kondisi stabil",
                'tone' => 'green',
            ],
        };
    }

    private function snapshot(Patient $patient, int $index): array
    {
        $monitoring = $patient->relationLoaded('latestInfusionMonitoring')
            ? $patient->latestInfusionMonitoring
            : $patient->latestInfusionMonitoring()->with('latestReading')->first();

        if ($monitoring) {
            $overrideSnapshot = app(OperatorOverrideService::class)->snapshotForMonitoring($monitoring, $patient);

            if ($overrideSnapshot) {
                return $overrideSnapshot;
            }
        }

        $reading = $monitoring?->latestReading;

        if ($reading) {
            $percentage = (int) round($reading->remaining_percentage);
            $calculator = app(InfusionCalculator::class);
            $isOffline = $this->isOffline($reading);
            $deviceStatus = $isOffline ? 'offline' : $reading->device_status;

            return [
                'percentage' => $percentage,
                'currentWeight' => (int) round($reading->weight),
                'timeRemaining' => $deviceStatus === 'normal'
                    ? $calculator->timeRemainingFromWeightTrend($monitoring, $reading)
                    : '-',
                'cardStatus' => $this->readingCardStatus($deviceStatus, $reading->remaining_percentage),
                'statusLabel' => $this->readingStatusLabel($deviceStatus, $reading->remaining_percentage),
                'statusTone' => $this->readingStatusTone($deviceStatus, $reading->remaining_percentage),
                'weightTone' => $deviceStatus === 'normal' && $reading->remaining_percentage > 20 ? 'green' : 'red',
                'progressTone' => $this->readingProgressTone($deviceStatus, $reading->remaining_percentage),
                'isOffline' => $isOffline,
                'hasReading' => true,
                'monitoring' => $monitoring,
            ];
        }

        $isOffline = $this->isMonitoringOfflineWithoutReading($monitoring);
        $capacity = max(0, (int) ($monitoring?->capacity_ml ?: $patient->initial_volume ?: 0));

        return [
            'percentage' => $capacity > 0 ? 100 : 0,
            'currentWeight' => $capacity > 0 ? $capacity : 0,
            'timeRemaining' => $isOffline ? '-' : 'Menghitung',
            'cardStatus' => 'warning',
            'statusLabel' => $isOffline ? 'Offline' : 'Menunggu',
            'statusTone' => $isOffline ? 'red' : 'green',
            'weightTone' => $capacity > 0 ? 'green' : 'red',
            'progressTone' => 'yellow',
            'isOffline' => $isOffline,
            'hasReading' => $isOffline,
            'monitoring' => $monitoring,
        ];
    }

    private function isOffline(InfusionReading $reading): bool
    {
        $seconds = max(5, (int) config('infusion.offline_seconds', 30));

        return $reading->logged_at->lt(now()->subSeconds($seconds));
    }

    private function isMonitoringOfflineWithoutReading(?InfusionMonitoring $monitoring): bool
    {
        if (! $monitoring) {
            return false;
        }

        $seconds = max(5, (int) config('infusion.offline_seconds', 30));
        $referenceTime = $monitoring->started_at ?? $monitoring->created_at;

        if (! $referenceTime) {
            return false;
        }

        return $referenceTime->lt(now()->subSeconds($seconds));
    }

    private function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name)) ?: [];
        $initials = collect($words)
            ->filter()
            ->take(2)
            ->map(fn (string $word): string => strtoupper(substr($word, 0, 1)))
            ->implode('');

        return $initials !== '' ? $initials : 'PS';
    }

    private function readingCardStatus(string $deviceStatus, float $percentage): string
    {
        if ($deviceStatus === 'offline') {
            return 'warning';
        }

        if ($deviceStatus === 'habis' || $percentage <= $this->emptyPercentage()) {
            return 'critical';
        }

        if ($deviceStatus === 'macet' || $deviceStatus === 'warning' || $percentage <= $this->lowPercentage()) {
            return 'warning';
        }

        return 'normal';
    }

    private function readingStatusLabel(string $deviceStatus, float $percentage): string
    {
        if ($deviceStatus === 'offline') {
            return 'Offline';
        }

        if ($deviceStatus === 'habis' || $percentage <= $this->emptyPercentage()) {
            return 'Ganti';
        }

        if ($deviceStatus === 'macet') {
            return 'Macet';
        }

        if ($deviceStatus === 'warning' || $percentage <= $this->lowPercentage()) {
            return 'Peringatan';
        }

        return 'Normal';
    }

    private function readingStatusTone(string $deviceStatus, float $percentage): string
    {
        if ($deviceStatus === 'offline') {
            return 'red';
        }

        if ($deviceStatus === 'habis' || $percentage <= $this->emptyPercentage()) {
            return 'cyan';
        }

        if ($deviceStatus === 'macet' || $deviceStatus === 'warning' || $percentage <= $this->lowPercentage()) {
            return 'red';
        }

        return 'green';
    }

    private function readingProgressTone(string $deviceStatus, float $percentage): string
    {
        if ($deviceStatus === 'offline') {
            return 'yellow';
        }

        if ($deviceStatus === 'habis' || $percentage <= $this->emptyPercentage()) {
            return 'red';
        }

        if ($deviceStatus === 'macet' || $deviceStatus === 'warning' || $percentage <= $this->lowPercentage()) {
            return 'yellow';
        }

        return 'green';
    }

    private function monitoringStatusLabel(?string $status): string
    {
        return match ($status) {
            'aktif' => 'Aktif',
            'bermasalah' => 'Bermasalah',
            'diganti' => 'Infus Diganti',
            'selesai' => 'Selesai Monitoring',
            default => 'Belum Ada Monitoring',
        };
    }

    private function monitoringStatusTone(?string $status): string
    {
        return match ($status) {
            'aktif' => 'green',
            'bermasalah' => 'red',
            'diganti', 'selesai' => 'cyan',
            default => 'cyan',
        };
    }

    private function formatNumber(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    private function infusionName(InfusionMonitoring $monitoring): string
    {
        return $monitoring->infusion_name ?: 'Infus ' . $monitoring->capacity_ml . ' ml';
    }

    private function locationLabel(Patient $patient, ?InfusionMonitoring $monitoring): string
    {
        $bedLabel = $this->monitoringBedLabel($monitoring) ?: $this->bedLabel($patient->bed_number);

        if (! $bedLabel) {
            return $patient->room_name;
        }

        return "{$patient->room_name} - {$bedLabel}";
    }

    private function bedLabel(?int $bedNumber): ?string
    {
        if (! $bedNumber) {
            return null;
        }

        return config("infusion.beds.{$bedNumber}.label", 'Bed ' . $bedNumber);
    }

    private function monitoringBedLabel(?InfusionMonitoring $monitoring): ?string
    {
        if (! $monitoring) {
            return null;
        }

        foreach (config('infusion.beds', []) as $bedNumber => $bed) {
            if ((int) $bed['node_id'] === (int) $monitoring->node_id) {
                return $bed['label'] ?? 'Bed ' . $bedNumber;
            }
        }

        return $monitoring->unit_infus ?: null;
    }

    private function alertMessage(string $statusLabel, Patient $patient, int $percentage): string
    {
        return match ($statusLabel) {
            'Offline' => "Node infus {$patient->patient_name} tidak mengirim data.",
            'Ganti' => "Infus {$patient->patient_name} perlu diganti, sisa {$percentage}%.",
            'Macet' => "Aliran infus {$patient->patient_name} terdeteksi macet.",
            default => "Infus {$patient->patient_name} hampir habis, sisa {$percentage}%.",
        };
    }

    private function lowPercentage(): float
    {
        return app(InfusionCalculator::class)->lowPercentage();
    }

    private function emptyPercentage(): float
    {
        return app(InfusionCalculator::class)->emptyPercentage();
    }
}
