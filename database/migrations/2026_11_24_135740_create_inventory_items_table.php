<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();$table->string('sku_code')->unique();
            $table->string('name');$table->string('category'); // elektronik, kemasan, sparepart, bahan-baku
            $table->string('rack_location');$table->string('batch_or_note')->nullable();
            $table->unsignedInteger('stock')->default(0);$table->unsignedInteger('min_stock')->default(10);
            $table->string('unit')->default('Unit'); // Box, Palet, Unit, Roll$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};