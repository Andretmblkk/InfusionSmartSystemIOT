<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InfusionMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'node_id',
        'bed_number',
        'unit_infus',
        'infusion_name',
        'data_infus_id',
        'capacity_ml',
        'responsible_nurse',
        'perawat_penanggung_jawab_id',
        'started_at',
        'ended_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'capacity_ml' => 'integer',
            'node_id' => 'integer',
            'bed_number' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function infusionProduct(): BelongsTo
    {
        return $this->belongsTo(InfusionProduct::class, 'data_infus_id');
    }

    public function responsibleNurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'perawat_penanggung_jawab_id');
    }

    public function readings(): HasMany
    {
        return $this->hasMany(InfusionReading::class);
    }

    public function latestReading(): HasOne
    {
        return $this->hasOne(InfusionReading::class)->latestOfMany('logged_at');
    }
}
