<?php

use Illuminate\Foundation\Inspiring;
use App\Models\InfusionMonitoring;
use App\Models\Patient;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('e2e:cleanup', function () {
    $patientIds = Patient::where('patient_name', 'like', 'E2E%')->pluck('id');

    $deletedMonitorings = InfusionMonitoring::whereIn('patient_id', $patientIds)->delete();
    $deleted = Patient::whereIn('id', $patientIds)->delete();

    $this->info("Deleted {$deleted} E2E patient records and {$deletedMonitorings} monitoring sessions.");
})->purpose('Remove E2E-generated patient records');
