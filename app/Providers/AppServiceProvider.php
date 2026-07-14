<?php

namespace App\Providers;

use App\Models\Patient;
use App\Models\User;
use App\Services\InfusionDisplayService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('use-operator-panel', fn (User $user): bool => (bool) $user->is_operator);

        View::composer('components.dashboard.topbar', function ($view): void {
            $user = Auth::user();
            $notifications = [];

            if ($user) {
                $activeNodeIds = collect(config('infusion.beds', []))
                    ->pluck('node_id')
                    ->map(fn ($nodeId): int => (int) $nodeId)
                    ->all();

                if ($activeNodeIds !== []) {
                    $patients = Patient::with('latestInfusionMonitoring.latestReading')
                        ->whereHas('infusionMonitorings', fn ($query) => $query
                            ->whereIn('status', ['aktif', 'bermasalah'])
                            ->whereIn('node_id', $activeNodeIds))
                        ->latest()
                        ->get();

                    $notifications = app(InfusionDisplayService::class)->alerts($patients);
                }
            }

            $view->with([
                'topbarUser' => $user,
                'topbarNotifications' => collect($notifications)->take(5)->values()->all(),
                'topbarNotificationCount' => count($notifications),
                'topbarRenderedAt' => now(),
            ]);
        });
    }
}
