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
        Schema::create('audios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedTinyInteger('is_file')->default(0)->comment('1=>file , 2 => link');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('can_skip')->default(0);
            $table->unsignedInteger('skip_rate')->default(0);
            $table->string('audio_type')->default(0);
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->foreign('parent_id')->references('id')->on('audios')->onDelete('set null');
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('audio_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audio_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['audio_id', 'locale']);
            $table->foreign('audio_id')->references('id')->on('audios')->onDelete('cascade');
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
