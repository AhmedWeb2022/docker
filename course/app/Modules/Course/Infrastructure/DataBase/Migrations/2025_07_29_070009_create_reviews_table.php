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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedInteger('follow_up')->default(0);
            $table->unsignedInteger('degree_focus')->default(0);
            $table->unsignedInteger('interacting_tasks')->default(0);
            $table->unsignedInteger('behavior_cooperation')->default(0);
            $table->unsignedInteger('progress_understanding')->default(0);
            $table->text('notes')->nullable();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
