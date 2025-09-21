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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedTinyInteger('is_free')->default(0);
            $table->unsignedTinyInteger('is_standalone')->default(0);
            $table->unsignedTinyInteger('type')->default(0)->comment('1=>online , 2 => offline , 3=> both');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('price')->default(0);
            $table->string('image')->nullable();
            $table->foreign('parent_id')->references('id')->on('lessons')->onDelete('set null');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('lesson_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['lesson_id', 'locale']);
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
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
