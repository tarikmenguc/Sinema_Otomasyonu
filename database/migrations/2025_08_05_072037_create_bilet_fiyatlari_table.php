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
        Schema::create('bilet_fiyatlari', function (Blueprint $table) {
            $table->id();
             $table->enum('uye_tipi', ['ogrenci', 'tam'])->unique();
             $table->integer('fiyat'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilet_fiyatlari');
    }
};
