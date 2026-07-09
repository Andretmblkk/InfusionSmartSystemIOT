<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infusion_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('node_id');
            $table->string('unit_infus');
            $table->unsignedInteger('capacity_ml')->default(500);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();

            $table->index(['status', 'node_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infusion_monitorings');
    }
};
