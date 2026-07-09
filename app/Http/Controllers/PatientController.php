<?php

namespace App\Http\Controllers;

use App\Models\InfusionMonitoring;
use App\Models\Patient;
use App\Services\InfusionDisplayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function create(): View
    {
        return view('pages.patient-input', [
            'bedOptions' => $this->bedOptions(),
            'bedOccupancies' => $this->bedOccupancies(),
        ]);
    }

    public function show(Patient $patient, InfusionDisplayService $display): View
    {
        $patient->load('latestInfusionMonitoring.latestReading');

        return view('pages.patient-detail', [
            'patient' => $patient,
            'detail' => $display->patientDetail($patient),
            'alerts' => $display->alerts(collect([$patient])),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_name' => ['required', 'string', 'max:255'],
            'room_name' => ['required', 'string', 'max:255'],
            'bed_number' => ['required', 'integer', Rule::in(array_keys($this->bedOptions()))],
            'doctor_name' => ['required', 'string', 'max:255'],
            'nurse_name' => ['required', 'string', 'max:255'],
            'initial_volume' => ['required', 'integer', 'min:1', 'max:5000'],
            'installed_at' => ['required', 'date'],
            'confirm_bed_transfer' => ['nullable', 'boolean'],
        ]);

        $bed = $this->bedOptions()[$validated['bed_number']];
        $nodeId = (int) $bed['node_id'];
        $activeMonitoring = InfusionMonitoring::with('patient')
            ->where('node_id', $nodeId)
            ->whereIn('status', $this->activeMonitoringStatuses())
            ->latest('id')
            ->first();

        if ($activeMonitoring && ! $request->boolean('confirm_bed_transfer')) {
            $patientName = $activeMonitoring->patient?->patient_name ?? 'pasien lain';

            return back()
                ->withInput()
                ->withErrors([
                    'bed_number' => "{$bed['label']} sedang dipakai pasien {$patientName}. Konfirmasi pengalihan terlebih dahulu.",
                ]);
        }

        $patientData = $validated;
        unset($patientData['confirm_bed_transfer']);

        DB::transaction(function () use ($patientData): void {
            $bed = $this->bedOptions()[$patientData['bed_number']];
            $nodeId = (int) $bed['node_id'];

            InfusionMonitoring::query()
                ->where('node_id', $nodeId)
                ->whereIn('status', $this->activeMonitoringStatuses())
                ->update([
                    'ended_at' => now(),
                    'status' => 'selesai',
                ]);

            $patient = Patient::create($patientData);

            $patient->infusionMonitorings()->create([
                'node_id' => $nodeId,
                'bed_number' => $patientData['bed_number'],
                'unit_infus' => $bed['label'],
                'capacity_ml' => (int) $patientData['initial_volume'],
                'started_at' => $patient->installed_at,
                'ended_at' => null,
                'status' => 'aktif',
            ]);
        });

        return redirect()
            ->route('monitoring')
            ->with('status', 'Data pasien berhasil disimpan dan monitoring dimulai.');
    }

    public function replaceInfusion(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'replacement_volume' => ['required', 'integer', 'min:1', 'max:5000'],
            'replaced_at' => ['required', 'date'],
        ]);

        $created = DB::transaction(function () use ($patient, $validated): bool {
            $monitoring = $patient->infusionMonitorings()
                ->whereIn('status', $this->activeMonitoringStatuses())
                ->latest('id')
                ->first();

            if (! $monitoring) {
                return false;
            }

            $monitoring->update([
                'ended_at' => $validated['replaced_at'],
                'status' => 'diganti',
            ]);

            $patient->update([
                'initial_volume' => (int) $validated['replacement_volume'],
                'installed_at' => $validated['replaced_at'],
            ]);

            $patient->infusionMonitorings()->create([
                'node_id' => $monitoring->node_id,
                'bed_number' => $monitoring->bed_number,
                'unit_infus' => $monitoring->unit_infus,
                'capacity_ml' => (int) $validated['replacement_volume'],
                'started_at' => $validated['replaced_at'],
                'ended_at' => null,
                'status' => 'aktif',
            ]);

            return true;
        });

        if (! $created) {
            return back()->withErrors([
                'replacement_volume' => 'Tidak ada sesi monitoring aktif untuk pasien ini.',
            ]);
        }

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Infus baru berhasil dimulai untuk kasur yang sama.');
    }

    private function bedOptions(): array
    {
        return config('infusion.beds', []);
    }

    private function bedOccupancies(): array
    {
        return InfusionMonitoring::with('patient')
            ->whereIn('status', $this->activeMonitoringStatuses())
            ->get()
            ->mapWithKeys(function (InfusionMonitoring $monitoring): array {
                $bedNumber = (int) ($monitoring->bed_number ?: $monitoring->node_id);

                if ($bedNumber < 1 || ! $monitoring->patient) {
                    return [];
                }

                return [
                    $bedNumber => [
                        'bed_label' => $monitoring->unit_infus ?: config("infusion.beds.{$bedNumber}.label", 'Kasur ' . $bedNumber),
                        'patient_name' => $monitoring->patient->patient_name,
                        'room_name' => $monitoring->patient->room_name,
                    ],
                ];
            })
            ->all();
    }

    private function activeMonitoringStatuses(): array
    {
        return ['aktif', 'bermasalah'];
    }
}
