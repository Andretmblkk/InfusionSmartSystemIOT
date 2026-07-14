<?php

namespace Database\Seeders;

use App\Models\InfusionMonitoring;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class InfusionDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::oldest('id')->take(3)->get();

        foreach ([1, 2, 3] as $node) {
            $patient = $patients->get($node - 1);
            $bed = config("infusion.beds.{$node}", ['label' => 'Bed ' . $node, 'node_id' => $node]);

            InfusionMonitoring::updateOrCreate(
                [
                    'node_id' => $node,
                    'status' => 'aktif',
                ],
                [
                    'patient_id' => $patient?->id,
                    'bed_number' => $node,
                    'unit_infus' => $bed['label'],
                    'capacity_ml' => (int) ($patient?->initial_volume ?? 500),
                    'started_at' => now(),
                    'ended_at' => null,
                    'status' => 'aktif',
                ],
            );
        }
    }
}
