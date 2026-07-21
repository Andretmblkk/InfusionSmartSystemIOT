<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperatorOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'bed_number',
        'infusion_monitoring_id',
        'operator_user_id',
        'active',
        'condition',
        'flow_profile',
        'has_flow_started',
        'baseline_weight',
        'baseline_percentage',
        'baseline_recorded_at',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'node_id' => 'integer',
            'bed_number' => 'integer',
            'infusion_monitoring_id' => 'integer',
            'operator_user_id' => 'integer',
            'active' => 'boolean',
            'has_flow_started' => 'boolean',
            'baseline_weight' => 'float',
            'baseline_percentage' => 'float',
            'baseline_recorded_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }
}
