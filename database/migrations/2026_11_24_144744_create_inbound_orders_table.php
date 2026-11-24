<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbound_orders', function (Blueprint $table) {
            $table->id();
            $table->string('inbound_number')->unique();
            $table->string('supplier_name');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('unit')->default('Box');
            $table->string('target_rack');
            $table->enum('status', ['menunggu_qc', 'selesai'])->default('menunggu_qc');
            $table->boolean('stock_applied')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_orders');
    }
};