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
            'bedLabel' => $snapshot['monitoring']?->unit_infus ?? $this->bedLabel($patient->bed_number),
            'nodeId' => $snapshot['monitoring']?->node_id,
        ];
    }

    public function alerts(Collection $patients): array
    {
        return $patients->values()
            ->map(function (Patient $patient, int $index): ?array {
                $snapshot = $this->snapshot($patient, $index);

                if (! $snapshot['hasReading'] || $snapshot['cardStatus'] === 'normal') {
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
        $readings = InfusionReading::with('monitoring.patient')
            ->latest('logged_at')
            ->take(8)
            ->get();

        if ($readings->isNotEmpty()) {
            return $readings->map(function (InfusionReading $reading): array {
                $patient = $reading->monitoring->patient;

                return [
                    'time' => optional($reading->logged_at)->format('d M Y, H:i') ?? '-',
                    'patient' => $patient?->patient_name ?? 'Node ' . $reading->node_id,
                    'room' => $patient ? $this->locationLabel($patient, $reading->monitoring) : $reading->unit_infus,
                    'weight' => $this->formatNumber($reading->weight) . ' gram',
                    'percentage' => $this->formatNumber($reading->remaining_percentage) . '%',
                    'status' => $this->readingStatusLabel($reading->device_status, $reading->remaining_percentage),
                    'tone' => $this->readingStatusTone($reading->device_status, $reading->remaining_percentage),
                ];
            })->all();
        }

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

    private function snapshot(Patient $patient, int $index): array
    {
        $monitoring = $patient->relationLoaded('latestInfusionMonitoring')
            ? $patient->latestInfusionMonitoring
            : $patient->latestInfusionMonitoring()->with('latestReading')->first();
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

        return [
            'percentage' => 0,
            'currentWeight' => 0,
            'timeRemaining' => 'Menghitung',
            'cardStatus' => 'warning',
            'statusLabel' => 'Menunggu',
            'statusTone' => 'red',
            'weightTone' => 'red',
            'progressTone' => 'yellow',
            'isOffline' => false,
            'hasReading' => false,
            'monitoring' => $monitoring,
        ];
    }

    private function isOffline(InfusionReading $reading): bool
    {
        $seconds = max(5, (int) config('infusion.offline_seconds', 30));

        return $reading->logged_at->lt(now()->subSeconds($seconds));
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

    private function formatNumber(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    private function locationLabel(Patient $patient, ?InfusionMonitoring $monitoring): string
    {
        $bedLabel = $monitoring?->unit_infus ?: $this->bedLabel($patient->bed_number);

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

        return config("infusion.beds.{$bedNumber}.label", 'Kasur ' . $bedNumber);
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
