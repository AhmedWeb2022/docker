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
        Schema::table('diploma_contents', function (Blueprint $table) {
            $table->unsignedBigInteger('diploma_level_track_id')->nullable()->change();
            $table->unsignedBigInteger('diploma_level_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_contents', function (Blueprint $table) {
            $table->foreignId('diploma_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('diploma_level_track_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('diploma_level_id')->constrained()->onDelete('cascade')->change();
        });

        
    }
};
