<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infusion_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infusion_monitoring_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('node_id');
            $table->string('unit_infus');
            $table->dateTime('logged_at');
            $table->decimal('weight', 8, 2)->default(0);
            $table->decimal('drip_rate_tpm', 8, 2)->default(0);
            $table->decimal('remaining_percentage', 5, 2)->default(0);
            $table->string('device_status')->default('normal');
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['node_id', 'logged_at']);
            $table->index(['device_status', 'logged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infusion_readings');
    }
};

