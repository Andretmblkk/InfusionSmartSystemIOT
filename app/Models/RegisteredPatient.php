<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegisteredPatient extends Model
{
    use HasFactory;

    protected $table = 'data_pasien';

    protected $fillable = [
        'nomor_rekam_medis',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'alergi',
        'alamat',
        'telepon',
        'nama_penanggung_jawab',
        'telepon_penanggung_jawab',
        'jenis_jaminan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'aktif' => 'boolean',
        ];
    }

    public function monitoringPatients(): HasMany
    {
        return $this->hasMany(Patient::class, 'data_pasien_id');
    }
}
