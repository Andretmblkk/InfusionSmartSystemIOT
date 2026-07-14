<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InfusionProduct extends Model
{
    use HasFactory;

    protected $table = 'data_infus';

    protected $fillable = [
        'nama',
        'kategori',
        'volume_default_ml',
        'pabrikan',
        'catatan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'volume_default_ml' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(InfusionMonitoring::class, 'data_infus_id');
    }
}
