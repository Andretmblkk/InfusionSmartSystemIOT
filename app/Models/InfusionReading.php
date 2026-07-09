<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfusionReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'infusion_monitoring_id',
        'node_id',
        'unit_infus',
        'logged_at',
        'weight',
        'drip_rate_tpm',
        'remaining_percentage',
        'device_status',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
            'weight' => 'float',
            'drip_rate_tpm' => 'float',
            'remaining_percentage' => 'float',
            'payload' => 'array',
            'node_id' => 'integer',
        ];
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(InfusionMonitoring::class, 'infusion_monitoring_id');
    }
}

