<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\InfusionReadingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\MonitoringHistoryController;
use App\Http\Controllers\PatientController;

Route::post('/api.php', [InfusionReadingController::class, 'store'])->name('legacy.infusion-readings.api-php');
Route::post('/update_data.php', [InfusionReadingController::class, 'store'])->name('legacy.infusion-readings.update-data');

Route::get('/', function () {
    return view('pages.login');
})->middleware('guest')->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'employee_id' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember_shift'))) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    return back()
        ->withErrors(['employee_id' => 'NIP / ID karyawan atau kata sandi tidak sesuai.'])
        ->onlyInput('employee_id');
})->middleware('guest')->name('login.store');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::get('/patients/create', [PatientController::class, 'create'])->middleware('auth')->name('patients.create');
Route::post('/patients', [PatientController::class, 'store'])->middleware('auth')->name('patients.store');
Route::post('/patients/{patient}/replace-infusion', [PatientController::class, 'replaceInfusion'])->middleware('auth')->name('patients.replace-infusion');
Route::get('/patients/{patient}', [PatientController::class, 'show'])->middleware('auth')->name('patients.show');

Route::get('/monitoring', MonitoringController::class)->middleware('auth')->name('monitoring');
Route::get('/monitoring/history', MonitoringHistoryController::class)->middleware('auth')->name('monitoring.history');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');
