<?php

use App\Http\Controllers\Api\InfusionReadingController;
use Illuminate\Support\Facades\Route;

Route::post('/api.php', [InfusionReadingController::class, 'store'])->name('api.infusion-readings.legacy');
Route::post('/update_data.php', [InfusionReadingController::class, 'store'])->name('api.infusion-readings.update-data');
Route::post('/infusion-readings', [InfusionReadingController::class, 'store'])->name('api.infusion-readings.store');

