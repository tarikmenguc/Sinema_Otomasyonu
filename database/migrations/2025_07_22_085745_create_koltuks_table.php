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
        Schema::create('koltuks', function (Blueprint $table) {
            $table->id();
            $table->string("koltuk_no");
            $table->foreignId("Salon")->constrained("salons");
            $table->boolean("bos_mu")->default(true);
            $table->boolean("is_active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koltuks');
    }
};
