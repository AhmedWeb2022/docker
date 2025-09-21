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
        Schema::create('poll_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('poll_id')->nullable();
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->string('percentage')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('poll_answer_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_answer_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['poll_answer_id', 'locale']);
            $table->foreign('poll_answer_id')->references('id')->on('poll_answers')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_answers');
    }
};
