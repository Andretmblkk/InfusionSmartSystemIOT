<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operator_overrides', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('node_id')->unique();
            $table->unsignedSmallInteger('bed_number')->nullable();
            $table->foreignId('operator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('active')->default(false);
            $table->string('condition')->default('normal');
            $table->string('flow_profile')->default('pending');
            $table->boolean('has_flow_started')->default(false);
            $table->decimal('baseline_weight', 10, 2)->default(0);
            $table->decimal('baseline_percentage', 6, 2)->default(100);
            $table->timestamp('baseline_recorded_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operator_overrides');
    }
};
