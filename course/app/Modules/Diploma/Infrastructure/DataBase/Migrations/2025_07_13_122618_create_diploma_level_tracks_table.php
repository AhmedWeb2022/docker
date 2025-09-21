<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diploma_level_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_id')->constrained()->onDelete('cascade');
            $table->foreignId('diploma_level_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('diploma_level_track_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_level_track_id')->constrained('diploma_level_tracks')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unique(['diploma_level_track_id', 'locale'], 'track_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deploma_levels');
    }
};
