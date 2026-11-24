<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_name'); // Wingbox B 9241 KYT
            $table->string('plate_number')->unique();
            $table->string('driver_name');
            $table->enum('status', ['standby', 'dalam_perjalanan', 'perawatan'])->default('standby');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};