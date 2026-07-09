<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfusionMonitoring;
use App\Services\InfusionCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InfusionReadingController extends Controller
{
    public function store(Request $request, InfusionCalculator $calculator): JsonResponse
    {
        $payload = $request->json()->all() ?: $request->all();

        $node = (int) ($payload['node'] ?? 0);

        if ($node < 1 || $node > 3) {
            return response()->json([
                'success' => false,
                'message' => 'Node ID invalid (1-3)',
            ]);
        }

        $monitoring = InfusionMonitoring::query()
            ->whereIn('status', ['aktif', 'bermasalah'])
            ->where('node_id', $node)
            ->latest('id')
            ->first();

        if (! $monitoring) {
            return response()->json([
                'success' => false,
                'message' => "No Active Monitoring for Node {$node}",
            ]);
        }

        $weight = (float) ($payload['berat'] ?? 0);
        $reportedPercentage = $payload['volume'] ?? $payload['persen'] ?? null;
        $dripRate = (float) ($payload['laju'] ?? $payload['tpm'] ?? 0);
        $loggedAt = Carbon::now(config('app.timezone'));
        $remainingPercentage = $calculator->percentage(
            $reportedPercentage === null ? null : (float) $reportedPercentage,
            $weight,
            $monitoring->capacity_ml,
        );
        $deviceStatus = $calculator->deviceStatus(
            $monitoring,
            $weight,
            $loggedAt,
            $payload['status_infus'] ?? $payload['status'] ?? null,
            $remainingPercentage,
        );

        $reading = $monitoring->readings()->create([
            'node_id' => $node,
            'unit_infus' => $monitoring->unit_infus,
            'logged_at' => $loggedAt,
            'weight' => $weight,
            'drip_rate_tpm' => $dripRate,
            'remaining_percentage' => $remainingPercentage,
            'device_status' => $deviceStatus,
            'payload' => $payload,
        ]);

        $monitoring->update([
            'status' => $calculator->monitoringStatus($reading->device_status),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil',
            'node' => $node,
            'waktu' => $loggedAt->format('Y-m-d H:i:s'),
        ]);
    }
}
