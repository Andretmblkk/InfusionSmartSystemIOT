<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rekam_medis')->unique();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('golongan_darah', 3)->nullable();
            $table->text('alergi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('nama_penanggung_jawab')->nullable();
            $table->string('telepon_penanggung_jawab')->nullable();
            $table->string('jenis_jaminan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_lengkap', 'nomor_rekam_medis']);
        });

        Schema::create('data_dokter', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pegawai')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->string('spesialis')->nullable();
            $table->string('unit')->nullable();
            $table->string('telepon')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_lengkap', 'unit']);
        });

        Schema::create('data_perawat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pegawai')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->string('unit')->nullable();
            $table->string('telepon')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_lengkap', 'unit']);
        });

        Schema::create('data_infus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori')->nullable();
            $table->unsignedInteger('volume_default_ml')->default(500);
            $table->string('pabrikan')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['nama', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_infus');
        Schema::dropIfExists('data_perawat');
        Schema::dropIfExists('data_dokter');
        Schema::dropIfExists('data_pasien');
    }
};
