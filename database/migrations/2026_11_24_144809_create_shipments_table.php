<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('outbound_order_id')->nullable()->constrained('outbound_orders')->nullOnDelete();
            $table->foreignId('fleet_id')->constrained('fleets')->cascadeOnDelete();
            $table->string('destination');
            $table->string('eta_label');
            $table->enum('status', ['dalam_perjalanan', 'terkirim'])->default('dalam_perjalanan');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};