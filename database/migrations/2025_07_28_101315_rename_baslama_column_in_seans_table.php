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
        Schema::table('seans', function (Blueprint $table) {
            $table->renameColumn('başlama_zamani', 'baslama_zamani');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seans', function (Blueprint $table) {
             $table->renameColumn('baslama_zamani', 'başlama_zamani');
        });
    }
};
