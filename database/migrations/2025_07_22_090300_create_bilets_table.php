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
        Schema::create('bilets', function (Blueprint $table) {
            $table->id();
             $table->integer("fiyat");
             $table->foreignId("user_id")->constrained("users");
             $table->foreignId("seans_id")->constrained("seans");
            $table->foreignId("koltuk_id")->constrained("koltuks");
             $table->boolean("is_active");
            $table->timestamps();

             // Aynı seans + koltuk için tekrar bilet satılmasın
    $table->unique(['seans_id', 'koltuk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilets');
    }
};
