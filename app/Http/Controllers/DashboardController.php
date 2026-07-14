<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\InfusionDisplayService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(InfusionDisplayService $display): View
    {
        $activeNodeIds = collect(config('infusion.beds', []))->pluck('node_id')->map(fn ($nodeId): int => (int) $nodeId)->all();

        $patients = Patient::with('latestInfusionMonitoring.latestReading')
            ->whereHas('infusionMonitorings', fn ($query) => $query
                ->whereIn('status', ['aktif', 'bermasalah'])
                ->whereIn('node_id', $activeNodeIds))
            ->latest()
            ->paginate(4);
        $patientCollection = $patients->getCollection();
        $activePatients = Patient::with('latestInfusionMonitoring.latestReading')
            ->whereHas('infusionMonitorings', fn ($query) => $query
                ->whereIn('status', ['aktif', 'bermasalah'])
                ->whereIn('node_id', $activeNodeIds))
            ->latest()
            ->get();

        return view('pages.dashboard', [
            'stats' => $display->stats($activePatients),
            'dashboardPatients' => $display->dashboardRows($patientCollection),
            'alerts' => $display->alerts($patientCollection),
            'activityPanel' => $display->dashboardActivityPanel($activePatients),
            'patientPaginator' => $patients,
            'totalPatients' => $patients->total(),
        ]);
    }
}
