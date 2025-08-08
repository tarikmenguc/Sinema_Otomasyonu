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
             $table->string('transaction_id', 40)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bilets', function (Blueprint $table) {
            $table->string('transaction_id', 20)->change();
        });
    }
};
