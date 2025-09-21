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
        Schema::table('diploma_level_tracks', function (Blueprint $table) {
            // $table->unsignedBigInteger('diploma_level_id')->nullable()->change();
            // $table->dropForeign(['diploma_level_id']);
            // $table->dropIndex('diploma_level_tracks_diploma_level_id_index');
            // $table->index('diploma_level_id', 'diploma_level_tracks_diploma_level_id_index');

            // $table->dropUnique('diploma_level_tracks_diploma_id_diploma_level_id_unique');
            // $table->unique(['diploma_id', 'diploma_level_id'], 'diploma_level_tracks_diploma_id_diploma_level_id_unique');

            // $table->dropIfExists('diploma_level_tracks_diploma_id_index');
            // $table->index('diploma_id', 'diploma_level_tracks_diploma_id_index');
            // $table->foreign('diploma_level_id')->references('id')->on('diploma_levels')->onDelete('set null')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_level_tracks', function (Blueprint $table) {
            $table->foreignId('diploma_level_id')->constrained()->onDelete('set null')->change();
        });
    }
};
