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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
             $table->string('title');
            $table->string('year', 4)->nullable();                
            $table->string('released')->nullable();                  
            $table->string('runtime', 50)->nullable();             
            $table->string('genre')->nullable();                  
            $table->string('director')->nullable();
            $table->string('writer')->nullable();
            $table->string('actors')->nullable();
            $table->text('plot')->nullable();
            $table->text('awards')->nullable();
            $table->string('poster')->nullable();// URL
            $table->json('ratings')->nullable(); // JSON array
            $table->string('language')->nullable();
            $table->decimal('imdb_rating', 3, 1)->nullable();      
            $table->string('imdb_id')->unique(); // "tt1375666"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
