<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operator_overrides', function (Blueprint $table): void {
            if (! Schema::hasColumn('operator_overrides', 'infusion_monitoring_id')) {
                $table->foreignId('infusion_monitoring_id')
                    ->nullable()
                    ->after('bed_number')
                    ->constrained('infusion_monitorings')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('operator_overrides', function (Blueprint $table): void {
            if (Schema::hasColumn('operator_overrides', 'infusion_monitoring_id')) {
                $table->dropConstrainedForeignId('infusion_monitoring_id');
            }
        });
    }
};
