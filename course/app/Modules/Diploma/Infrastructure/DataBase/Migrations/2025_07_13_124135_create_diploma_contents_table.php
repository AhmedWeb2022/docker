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
        Schema::create('diploma_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_id')->constrained()->onDelete('cascade');
            $table->foreignId('diploma_level_track_id')->constrained()->onDelete('cascade');
            $table->foreignId('diploma_level_id')->constrained()->onDelete('cascade');
            $table->integer('order')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diploma_contents');
    }
};
