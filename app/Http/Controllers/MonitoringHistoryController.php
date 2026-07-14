<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\InfusionDisplayService;
use Illuminate\View\View;

class MonitoringHistoryController extends Controller
{
    public function __invoke(InfusionDisplayService $display): View
    {
        $activeNodeIds = collect(config('infusion.beds', []))->pluck('node_id')->map(fn ($nodeId): int => (int) $nodeId)->all();
        $patients = Patient::with(['registeredPatient', 'latestInfusionMonitoring.latestReading', 'infusionMonitorings.latestReading'])
            ->whereHas('infusionMonitorings', fn ($query) => $query->whereIn('node_id', $activeNodeIds))
            ->latest()
            ->get();

        return view('pages.monitoring-history', [
            'historyRows' => $display->recentHistory($patients),
        ]);
    }

    public function show(Patient $patient, InfusionDisplayService $display): View
    {
        $patient->load([
            'registeredPatient',
            'latestInfusionMonitoring.latestReading',
            'infusionMonitorings.latestReading',
        ]);

        return view('pages.monitoring-report-detail', [
            'patient' => $patient,
            'detail' => $display->patientDetail($patient),
            'reportRow' => $display->reportRow($patient),
        ]);
    }
}
