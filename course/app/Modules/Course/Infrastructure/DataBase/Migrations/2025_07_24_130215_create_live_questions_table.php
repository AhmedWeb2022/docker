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
        Schema::create('live_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('live_questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->tinyInteger('question_type')->comment('1 => MCQ, 2 => T/F , 3 => complete , 4 => match , 5 => paragraph , 6 => match children')->nullable();
            $table->tinyInteger('identicality')->comment('1 => identical , 2 => identical by percentage , 3 => manual correction')->nullable();
            $table->integer('identicality_percentage')->nullable();
            $table->tinyInteger('difficulty')->comment('1 => easy , 2 => medium , 3 => hard')->nullable();
            $table->integer('difficulty_level')->nullable();
            $table->longText('question')->nullable();
            $table->integer('degree')->nullable();
            $table->string('time')->nullable();
            $table->integer('creator')->nullable();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_questions');
    }
};
