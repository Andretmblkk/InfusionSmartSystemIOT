<?php

namespace App\Http\Controllers;

use App\Models\InfusionMonitoring;
use App\Models\OperatorOverride;
use App\Services\OperatorOverrideService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OperatorPanelController extends Controller
{
    public function index(OperatorOverrideService $service): View
    {
        $activeNodeIds = collect(config('infusion.beds', []))
            ->pluck('node_id')
            ->map(fn ($nodeId): int => (int) $nodeId)
            ->all();

        $monitorings = InfusionMonitoring::with(['patient', 'latestReading'])
            ->whereIn('status', ['aktif', 'bermasalah'])
            ->whereIn('node_id', $activeNodeIds)
            ->latest('id')
            ->get()
            ->unique('node_id')
            ->values();

        $overrides = OperatorOverride::query()
            ->whereIn('node_id', $activeNodeIds)
            ->get();

        return view('pages.operator-panel', [
            'operatorCards' => $service->panelCards($monitorings, $overrides),
            'flowProfiles' => $service->flowProfiles(),
        ]);
    }

    public function setCondition(Request $request, int $bedNumber, OperatorOverrideService $service): RedirectResponse
    {
        $validated = $request->validate([
            'condition' => ['required', 'string', Rule::in(['normal', 'offline', 'empty'])],
        ]);

        $bed = $this->bedOrFail($bedNumber);
        $monitoring = $this->activeMonitoringForNode((int) $bed['node_id']);

        if (! $monitoring) {
            return back()->withErrors([
                'condition' => 'Bed ini belum memiliki monitoring aktif untuk di-override.',
            ]);
        }

        $service->applyCondition(
            $request->user(),
            $bedNumber,
            (int) $bed['node_id'],
            $validated['condition'],
            $monitoring,
        );

        return back()->with('status', "Kondisi operator untuk {$bed['label']} berhasil diperbarui.");
    }

    public function setFlow(Request $request, int $bedNumber, OperatorOverrideService $service): RedirectResponse
    {
        $validated = $request->validate([
            'flow_profile' => ['required', 'string', Rule::in(array_keys($service->flowProfiles()))],
        ]);

        $bed = $this->bedOrFail($bedNumber);
        $monitoring = $this->activeMonitoringForNode((int) $bed['node_id']);

        if (! $monitoring) {
            return back()->withErrors([
                'flow_profile' => 'Bed ini belum memiliki monitoring aktif untuk di-override.',
            ]);
        }

        $service->applyFlowProfile(
            $request->user(),
            $bedNumber,
            (int) $bed['node_id'],
            $validated['flow_profile'],
            $monitoring,
        );

        return back()->with('status', "Laju simulasi untuk {$bed['label']} berhasil diperbarui.");
    }

    public function release(Request $request, int $bedNumber, OperatorOverrideService $service): RedirectResponse
    {
        $bed = $this->bedOrFail($bedNumber);

        $service->release($request->user(), (int) $bed['node_id']);

        return back()->with('status', "Override operator untuk {$bed['label']} dinonaktifkan.");
    }

    private function bedOrFail(int $bedNumber): array
    {
        $bed = config("infusion.beds.{$bedNumber}");

        abort_if(! is_array($bed), 404);

        return $bed;
    }

    private function activeMonitoringForNode(int $nodeId): ?InfusionMonitoring
    {
        return InfusionMonitoring::with(['patient', 'latestReading'])
            ->whereIn('status', ['aktif', 'bermasalah'])
            ->where('node_id', $nodeId)
            ->latest('id')
            ->first();
    }
}
