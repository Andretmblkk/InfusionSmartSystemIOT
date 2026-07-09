<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = [
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

    public function latestInfusionMonitoring(): HasOne
    {
        return $this->hasOne(InfusionMonitoring::class)
            ->whereIn('status', ['aktif', 'bermasalah'])
            ->latestOfMany();
    }
}
