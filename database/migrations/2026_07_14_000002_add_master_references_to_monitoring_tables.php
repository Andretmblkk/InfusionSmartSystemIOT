<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('data_pasien_id')->nullable()->after('id')->constrained('data_pasien')->nullOnDelete();
            $table->foreignId('data_dokter_id')->nullable()->after('bed_number')->constrained('data_dokter')->nullOnDelete();
            $table->foreignId('data_perawat_id')->nullable()->after('data_dokter_id')->constrained('data_perawat')->nullOnDelete();
        });

        Schema::table('infusion_monitorings', function (Blueprint $table) {
            $table->foreignId('data_infus_id')->nullable()->after('infusion_name')->constrained('data_infus')->nullOnDelete();
            $table->foreignId('perawat_penanggung_jawab_id')->nullable()->after('responsible_nurse')->constrained('data_perawat')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('infusion_monitorings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('perawat_penanggung_jawab_id');
            $table->dropConstrainedForeignId('data_infus_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('data_perawat_id');
            $table->dropConstrainedForeignId('data_dokter_id');
            $table->dropConstrainedForeignId('data_pasien_id');
        });
    }
};
