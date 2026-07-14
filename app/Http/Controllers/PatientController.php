<?php

namespace App\Http\Controllers;

use App\Models\InfusionMonitoring;
use App\Models\Doctor;
use App\Models\InfusionProduct;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\RegisteredPatient;
use App\Services\InfusionDisplayService;
use App\Services\OperatorOverrideService;
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
            'roomOptions' => $this->roomOptions(),
            'bedOccupancies' => $this->bedOccupancies(),
            'registeredPatients' => $this->registeredPatients(),
            'doctors' => $this->doctors(),
            'nurses' => $this->nurses(),
            'infusionProducts' => $this->infusionProducts(),
        ]);
    }

    public function show(Patient $patient, InfusionDisplayService $display): View
    {
        $patient->load('latestInfusionMonitoring.latestReading');

        return view('pages.patient-detail', [
            'patient' => $patient,
            'detail' => $display->patientDetail($patient),
            'alerts' => $display->alerts(collect([$patient])),
            'nurses' => $this->nurses(),
            'infusionProducts' => $this->infusionProducts(),
        ]);
    }

    public function store(Request $request, OperatorOverrideService $overrideService): RedirectResponse
    {
        $validated = $request->validate([
            'data_pasien_id' => ['required', 'integer', Rule::exists('data_pasien', 'id')->where('aktif', true)],
            'room_name' => ['required', 'string', Rule::in($this->roomOptions())],
            'bed_number' => ['required', 'integer', Rule::in(array_keys($this->bedOptions()))],
            'data_dokter_id' => ['required', 'integer', Rule::exists('data_dokter', 'id')->where('aktif', true)],
            'data_perawat_id' => ['required', 'integer', Rule::exists('data_perawat', 'id')->where('aktif', true)],
            'data_infus_id' => ['required', 'integer', Rule::exists('data_infus', 'id')->where('aktif', true)],
            'initial_volume' => ['required', 'integer', 'min:1', 'max:5000'],
            'installed_at' => ['required', 'date'],
            'confirm_bed_transfer' => ['nullable', 'boolean'],
        ]);

        $registeredPatient = RegisteredPatient::findOrFail($validated['data_pasien_id']);
        $doctor = Doctor::findOrFail($validated['data_dokter_id']);
        $nurse = Nurse::findOrFail($validated['data_perawat_id']);
        $infusionProduct = InfusionProduct::findOrFail($validated['data_infus_id']);

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

        $patientData = [
            'data_pasien_id' => $registeredPatient->id,
            'data_dokter_id' => $doctor->id,
            'data_perawat_id' => $nurse->id,
            'patient_name' => $registeredPatient->nama_lengkap,
            'room_name' => $validated['room_name'],
            'bed_number' => $validated['bed_number'],
            'doctor_name' => $this->doctorLabel($doctor),
            'nurse_name' => $nurse->nama_lengkap,
            'initial_volume' => (int) $validated['initial_volume'],
            'installed_at' => $validated['installed_at'],
        ];
        unset($patientData['confirm_bed_transfer']);

        DB::transaction(function () use ($patientData, $infusionProduct, $nurse): void {
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
                'infusion_name' => $infusionProduct->nama,
                'data_infus_id' => $infusionProduct->id,
                'capacity_ml' => (int) $patientData['initial_volume'],
                'responsible_nurse' => $patientData['nurse_name'],
                'perawat_penanggung_jawab_id' => $nurse->id,
                'started_at' => $patient->installed_at,
                'ended_at' => null,
                'status' => 'aktif',
            ]);
        });

        $overrideService->release($request->user(), $nodeId);

        return redirect()
            ->route('monitoring')
            ->with('status', 'Monitoring baru berhasil dibuat dan mulai aktif.');
    }

    public function replaceInfusion(Request $request, Patient $patient, OperatorOverrideService $overrideService): RedirectResponse
    {
        $validated = $request->validate([
            'pengganti_data_infus_id' => ['required', 'integer', Rule::exists('data_infus', 'id')->where('aktif', true)],
            'replacement_volume' => ['required', 'integer', 'min:1', 'max:5000'],
            'pengganti_data_perawat_id' => ['required', 'integer', Rule::exists('data_perawat', 'id')->where('aktif', true)],
            'replaced_at' => ['required', 'date'],
        ]);

        $infusionProduct = InfusionProduct::findOrFail($validated['pengganti_data_infus_id']);
        $nurse = Nurse::findOrFail($validated['pengganti_data_perawat_id']);

        $result = DB::transaction(function () use ($patient, $validated, $infusionProduct, $nurse): array {
            $monitoring = $patient->infusionMonitorings()
                ->whereIn('status', $this->activeMonitoringStatuses())
                ->latest('id')
                ->first();

            if (! $monitoring) {
                return ['created' => false, 'node_id' => null];
            }

            $monitoring->update([
                'ended_at' => $validated['replaced_at'],
                'status' => 'diganti',
            ]);

            $patient->update([
                'initial_volume' => (int) $validated['replacement_volume'],
                'data_perawat_id' => $nurse->id,
                'nurse_name' => $nurse->nama_lengkap,
                'installed_at' => $validated['replaced_at'],
            ]);

            $newMonitoring = $patient->infusionMonitorings()->create([
                'node_id' => $monitoring->node_id,
                'bed_number' => $this->bedNumberForNode($monitoring->node_id) ?? $monitoring->bed_number,
                'unit_infus' => $this->bedLabelForNode($monitoring->node_id) ?? $monitoring->unit_infus,
                'infusion_name' => $infusionProduct->nama,
                'data_infus_id' => $infusionProduct->id,
                'capacity_ml' => (int) $validated['replacement_volume'],
                'responsible_nurse' => $nurse->nama_lengkap,
                'perawat_penanggung_jawab_id' => $nurse->id,
                'started_at' => $validated['replaced_at'],
                'ended_at' => null,
                'status' => 'aktif',
            ]);

            $this->createInitialReading($newMonitoring, (string) $validated['replaced_at']);

            return ['created' => true, 'node_id' => $newMonitoring->node_id];
        });

        if (! ($result['created'] ?? false)) {
            return back()->withErrors([
                'replacement_volume' => 'Tidak ada sesi monitoring aktif untuk pasien ini.',
            ]);
        }

        if (! empty($result['node_id'])) {
            $overrideService->release($request->user(), (int) $result['node_id']);
        }

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Infus baru berhasil dimulai untuk bed yang sama.');
    }

    public function finishMonitoring(Request $request, Patient $patient, OperatorOverrideService $overrideService): RedirectResponse
    {
        $validated = $request->validate([
            'finished_at' => ['required', 'date'],
        ]);

        $result = DB::transaction(function () use ($patient, $validated): array {
            $monitoring = $patient->infusionMonitorings()
                ->whereIn('status', $this->activeMonitoringStatuses())
                ->latest('id')
                ->first();

            if (! $monitoring) {
                return ['finished' => false, 'node_id' => null];
            }

            $monitoring->update([
                'ended_at' => $validated['finished_at'],
                'status' => 'selesai',
            ]);

            return ['finished' => true, 'node_id' => $monitoring->node_id];
        });

        if (! ($result['finished'] ?? false)) {
            return back()->withErrors([
                'finished_at' => 'Tidak ada sesi monitoring aktif untuk pasien ini.',
            ]);
        }

        if (! empty($result['node_id'])) {
            $overrideService->release($request->user(), (int) $result['node_id']);
        }

        return redirect()
            ->route('monitoring')
            ->with('status', 'Monitoring pasien berhasil diselesaikan. Bed siap dipakai pasien baru.');
    }

    private function bedOptions(): array
    {
        return config('infusion.beds', []);
    }

    private function roomOptions(): array
    {
        return config('infusion.rooms', []);
    }

    private function registeredPatients()
    {
        return RegisteredPatient::where('aktif', true)
            ->orderBy('nama_lengkap')
            ->get();
    }

    private function doctors()
    {
        return Doctor::where('aktif', true)
            ->orderBy('nama_lengkap')
            ->get();
    }

    private function nurses()
    {
        return Nurse::where('aktif', true)
            ->orderBy('nama_lengkap')
            ->get();
    }

    private function infusionProducts()
    {
        return InfusionProduct::where('aktif', true)
            ->orderBy('nama')
            ->get();
    }

    private function doctorLabel(Doctor $doctor): string
    {
        return trim($doctor->nama_lengkap . ($doctor->spesialis ? ', ' . $doctor->spesialis : ''));
    }

    private function bedOccupancies(): array
    {
        return InfusionMonitoring::with('patient')
            ->whereIn('status', $this->activeMonitoringStatuses())
            ->whereIn('node_id', $this->activeNodeIds())
            ->get()
            ->mapWithKeys(function (InfusionMonitoring $monitoring): array {
                $bedNumber = $this->bedNumberForNode($monitoring->node_id) ?? (int) ($monitoring->bed_number ?: $monitoring->node_id);

                if ($bedNumber < 1 || ! $monitoring->patient) {
                    return [];
                }

                return [
                    $bedNumber => [
                        'bed_label' => config("infusion.beds.{$bedNumber}.label", $monitoring->unit_infus ?: 'Bed ' . $bedNumber),
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

    private function activeNodeIds(): array
    {
        return collect($this->bedOptions())
            ->pluck('node_id')
            ->map(fn ($nodeId): int => (int) $nodeId)
            ->values()
            ->all();
    }

    private function bedNumberForNode(int $nodeId): ?int
    {
        foreach ($this->bedOptions() as $bedNumber => $bed) {
            if ((int) $bed['node_id'] === $nodeId) {
                return (int) $bedNumber;
            }
        }

        return null;
    }

    private function bedLabelForNode(int $nodeId): ?string
    {
        $bedNumber = $this->bedNumberForNode($nodeId);

        return $bedNumber ? config("infusion.beds.{$bedNumber}.label") : null;
    }

    private function createInitialReading(InfusionMonitoring $monitoring, string $loggedAt): void
    {
        $monitoring->readings()->create([
            'node_id' => $monitoring->node_id,
            'unit_infus' => $monitoring->unit_infus,
            'logged_at' => $loggedAt,
            'weight' => (float) $monitoring->capacity_ml,
            'drip_rate_tpm' => 0,
            'remaining_percentage' => 100,
            'device_status' => 'normal',
            'payload' => [
                'source' => 'replace_infusion',
                'note' => 'Initial reading after infusion replacement.',
            ],
        ]);
    }
}
