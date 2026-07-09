<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'bed_number')) {
                $table->unsignedSmallInteger('bed_number')->nullable()->after('room_name');
            }
        });

        Schema::table('infusion_monitorings', function (Blueprint $table) {
            if (! Schema::hasColumn('infusion_monitorings', 'bed_number')) {
                $table->unsignedSmallInteger('bed_number')->nullable()->after('node_id');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            try {
                Schema::table('infusion_monitorings', function (Blueprint $table) {
                    $table->dropUnique('infusion_monitorings_node_id_unique');
                });
            } catch (Throwable) {
                //
            }
        }

        Schema::table('patients', function (Blueprint $table) {
            $table->index('bed_number');
        });

        Schema::table('infusion_monitorings', function (Blueprint $table) {
            $table->index(['bed_number', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('infusion_monitorings', function (Blueprint $table) {
            $table->dropIndex(['bed_number', 'status']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['bed_number']);
        });

        Schema::table('infusion_monitorings', function (Blueprint $table) {
            if (Schema::hasColumn('infusion_monitorings', 'bed_number')) {
                $table->dropColumn('bed_number');
            }
        });

        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'bed_number')) {
                $table->dropColumn('bed_number');
            }
        });
    }
};
