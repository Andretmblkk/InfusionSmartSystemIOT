<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\InfusionDisplayService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(InfusionDisplayService $display): View
    {
        $patients = Patient::with('latestInfusionMonitoring.latestReading')
            ->whereHas('infusionMonitorings', fn ($query) => $query->whereIn('status', ['aktif', 'bermasalah']))
            ->latest()
            ->paginate(4);
        $patientCollection = $patients->getCollection();
        $activePatients = Patient::with('latestInfusionMonitoring.latestReading')
            ->whereHas('infusionMonitorings', fn ($query) => $query->whereIn('status', ['aktif', 'bermasalah']))
            ->latest()
            ->get();

        return view('pages.dashboard', [
            'stats' => $display->stats($activePatients),
            'dashboardPatients' => $display->dashboardRows($patientCollection),
            'alerts' => $display->alerts($patientCollection),
            'patientPaginator' => $patients,
            'totalPatients' => $patients->total(),
        ]);
    }
}
