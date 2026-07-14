<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = [
        'data_pasien_id',
        'data_dokter_id',
        'data_perawat_id',
        'patient_name',
        'room_name',
        'bed_number',
        'doctor_name',
        'nurse_name',
        'initial_volume',
        'installed_at',
    ];

    protected function casts(): array
    {
        return [
            'initial_volume' => 'integer',
            'bed_number' => 'integer',
            'installed_at' => 'datetime',
        ];
    }

    public function infusionMonitorings(): HasMany
    {
        return $this->hasMany(InfusionMonitoring::class);
    }

    public function registeredPatient(): BelongsTo
    {
        return $this->belongsTo(RegisteredPatient::class, 'data_pasien_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'data_dokter_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'data_perawat_id');
    }

    public function latestInfusionMonitoring(): HasOne
    {
        return $this->hasOne(InfusionMonitoring::class)
            ->whereIn('status', ['aktif', 'bermasalah'])
            ->latestOfMany();
    }
}
