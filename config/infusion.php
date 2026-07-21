<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stagnation Detection
    |--------------------------------------------------------------------------
    |
    | An infusion is marked as blocked when the measured weight stays within
    | this tolerance for at least the configured number of minutes.
    |
    */

    'stagnation_minutes' => (int) env('INFUSION_STAGNATION_MINUTES', 1),
    'stagnation_tolerance_grams' => (float) env('INFUSION_STAGNATION_TOLERANCE_GRAMS', 10),
    'stagnation_start_below_percentage' => (float) env('INFUSION_STAGNATION_START_BELOW_PERCENTAGE', 98),
    'offline_seconds' => (int) env('INFUSION_OFFLINE_SECONDS', 30),
    'low_percentage' => (float) env('INFUSION_LOW_PERCENTAGE', 10),
    'empty_percentage' => (float) env('INFUSION_EMPTY_PERCENTAGE', 5),
    'estimation_window_minutes' => (int) env('INFUSION_ESTIMATION_WINDOW_MINUTES', 3),
    'estimation_min_samples' => (int) env('INFUSION_ESTIMATION_MIN_SAMPLES', 4),
    'estimation_min_drop_ml' => (float) env('INFUSION_ESTIMATION_MIN_DROP_ML', 2),
    'estimation_min_rate_ml_per_minute' => (float) env('INFUSION_ESTIMATION_MIN_RATE_ML_PER_MINUTE', 0.2),
    'estimation_max_minutes' => (int) env('INFUSION_ESTIMATION_MAX_MINUTES', 24 * 60),
    'page_refresh_seconds' => (int) env('INFUSION_PAGE_REFRESH_SECONDS', 15),

    'beds' => [
        1 => ['label' => 'Bed 1', 'node_id' => 2],
        2 => ['label' => 'Bed 2', 'node_id' => 3],
    ],

    'rooms' => [
        'VIP Dewasa',
        'Ruang Mawar',
        'Ruang Anggrek',
        'Ruang Nifas',
        'Ruang Isolasi',
        'Rawat Inap Anak',
    ],

    'operator_profiles' => [
        'pending' => ['label' => 'Belum Mengalir', 'rate_ml_per_hour' => 0],
        'slow' => ['label' => 'Lambat', 'rate_ml_per_hour' => 80],
        'medium' => ['label' => 'Sedang', 'rate_ml_per_hour' => 180],
        'fast' => ['label' => 'Cepat', 'rate_ml_per_hour' => 300],
        'stopped' => ['label' => 'Berhenti', 'rate_ml_per_hour' => 0],
    ],
];
