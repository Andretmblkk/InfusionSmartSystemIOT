<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infusion_monitorings', function (Blueprint $table) {
            if (! Schema::hasColumn('infusion_monitorings', 'infusion_name')) {
                $table->string('infusion_name')->nullable()->after('unit_infus');
            }

            if (! Schema::hasColumn('infusion_monitorings', 'responsible_nurse')) {
                $table->string('responsible_nurse')->nullable()->after('capacity_ml');
            }
        });
    }

    public function down(): void
    {
        Schema::table('infusion_monitorings', function (Blueprint $table) {
            if (Schema::hasColumn('infusion_monitorings', 'responsible_nurse')) {
                $table->dropColumn('responsible_nurse');
            }

            if (Schema::hasColumn('infusion_monitorings', 'infusion_name')) {
                $table->dropColumn('infusion_name');
            }
        });
    }
};
