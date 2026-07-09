<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\InfusionDisplayService;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function __invoke(InfusionDisplayService $display): View
    {
        $patients = Patient::with('latestInfusionMonitoring.latestReading')
            ->whereHas('infusionMonitorings', fn ($query) => $query->whereIn('status', ['aktif', 'bermasalah']))
            ->latest()
            ->get();

        return view('pages.monitoring', [
            'monitoringPatients' => $display->monitoringCards($patients),
            'alerts' => $display->alerts($patients),
            'stats' => $display->stats($patients),
        ]);
    }
}
