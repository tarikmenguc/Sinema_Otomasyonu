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
        Schema::table('koltuks', function (Blueprint $table) {
             $table->renameColumn('Salon', 'salon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('koltuks', function (Blueprint $table) {
             $table->renameColumn('salon_id', 'Salon');
        });
    }
};
