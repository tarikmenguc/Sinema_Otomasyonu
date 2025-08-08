<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bilets', function (Blueprint $table) {
            $table->enum('bilet_tipi', ['ogrenci', 'tam'])->after('fiyat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bilets', function (Blueprint $table) {
             $table->dropColumn('bilet_tipi');
        });
    }
};
