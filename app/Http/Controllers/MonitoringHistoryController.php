<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\InfusionDisplayService;
use Illuminate\View\View;

class MonitoringHistoryController extends Controller
{
    public function __invoke(InfusionDisplayService $display): View
    {
        $patients = Patient::with('latestInfusionMonitoring.latestReading')->latest()->get();

        return view('pages.monitoring-history', [
            'historyRows' => $display->recentHistory($patients),
        ]);
    }
}
