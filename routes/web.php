<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Controllers\Api\InfusionReadingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\MonitoringHistoryController;
use App\Http\Controllers\OperatorPanelController;
use App\Http\Controllers\PatientController;

Route::post('/api.php', [InfusionReadingController::class, 'store'])
    ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, ValidateCsrfToken::class])
    ->name('legacy.infusion-readings.api-php');
Route::post('/update_data.php', [InfusionReadingController::class, 'store'])
    ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, ValidateCsrfToken::class])
    ->name('legacy.infusion-readings.update-data');

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

Route::middleware('auth')->group(function (): void {
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('/master-data/patients', [MasterDataController::class, 'storePatient'])->name('master-data.patients.store');
    Route::post('/master-data/doctors', [MasterDataController::class, 'storeDoctor'])->name('master-data.doctors.store');
    Route::post('/master-data/nurses', [MasterDataController::class, 'storeNurse'])->name('master-data.nurses.store');
    Route::post('/master-data/infusions', [MasterDataController::class, 'storeInfusionProduct'])->name('master-data.infusions.store');
});

Route::get('/patients/create', [PatientController::class, 'create'])->middleware('auth')->name('patients.create');
Route::post('/patients', [PatientController::class, 'store'])->middleware('auth')->name('patients.store');
Route::post('/patients/{patient}/replace-infusion', [PatientController::class, 'replaceInfusion'])->middleware('auth')->name('patients.replace-infusion');
Route::post('/patients/{patient}/finish-monitoring', [PatientController::class, 'finishMonitoring'])->middleware('auth')->name('patients.finish-monitoring');
Route::get('/patients/{patient}', [PatientController::class, 'show'])->middleware('auth')->name('patients.show');

Route::get('/monitoring', MonitoringController::class)->middleware('auth')->name('monitoring');
Route::get('/monitoring/reports', MonitoringHistoryController::class)->middleware('auth')->name('monitoring.history');
Route::get('/monitoring/reports/{patient}', [MonitoringHistoryController::class, 'show'])->middleware('auth')->name('monitoring.history.show');

Route::middleware(['auth', 'can:use-operator-panel'])->group(function (): void {
    Route::get('/operator-panel', [OperatorPanelController::class, 'index'])->name('operator-panel.index');
    Route::post('/operator-panel/beds/{bedNumber}/condition', [OperatorPanelController::class, 'setCondition'])->name('operator-panel.condition');
    Route::post('/operator-panel/beds/{bedNumber}/flow', [OperatorPanelController::class, 'setFlow'])->name('operator-panel.flow');
    Route::post('/operator-panel/beds/{bedNumber}/release', [OperatorPanelController::class, 'release'])->name('operator-panel.release');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');
