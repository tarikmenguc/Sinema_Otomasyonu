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
        Schema::create('seans', function (Blueprint $table) {
            $table->id();
             $table->foreignId("film_id")->constrained("films");
            $table->foreignId("Salon_id")->constrained("salons");
            $table->dateTime("başlama_zamani");
            $table->dateTime("bitis_zamani");
            $table->boolean("is_active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seans');
    }
};
