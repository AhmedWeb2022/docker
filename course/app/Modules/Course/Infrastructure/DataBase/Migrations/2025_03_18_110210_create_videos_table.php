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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('videoable');
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->string('video_type')->nullable();
            $table->unsignedTinyInteger('is_file')->default(0)->comment('0=>link , 1=> file ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
