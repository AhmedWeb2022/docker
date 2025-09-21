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
        Schema::create('live_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedBigInteger('live_question_id')->nullable();
            $table->string('image')->nullable();
            $table->longText('answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('live_question_id')->references('id')->on('live_questions')->onDelete('cascade');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_answers');
    }
};
