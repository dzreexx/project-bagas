<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('warehouse_name')->default('Gudang Pusat Bekasi')->after('status');
            $table->string('warehouse_code')->default('WH-BKS-01')->after('warehouse_name');
            $table->unsignedTinyInteger('low_stock_threshold')->default(20)->after('warehouse_code');
            $table->string('theme_mode')->default('light')->after('low_stock_threshold'); // light | dark
            $table->string('font_size')->default('base')->after('theme_mode'); // sm | base | lg
            $table->string('accent_color')->default('indigo')->after('font_size'); // indigo | emerald | sky
            $table->boolean('compact_mode')->default(false)->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'warehouse_name',
                'warehouse_code',
                'low_stock_threshold',
                'theme_mode',
                'font_size',
                'accent_color',
                'compact_mode',
            ]);
        });
    }
};