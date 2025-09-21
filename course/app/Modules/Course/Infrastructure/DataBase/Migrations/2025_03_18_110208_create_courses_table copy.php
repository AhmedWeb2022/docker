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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('stage_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedTinyInteger('type')->default(0)->comment('1=>online , 2 => offline , 3=> both');
            $table->unsignedTinyInteger('status')->default(0)->comment('0 => inActive , 1 => Active');
            $table->unsignedTinyInteger('is_private')->default(0)->comment('0 => public , 1 => private');
            $table->unsignedTinyInteger('has_website')->default(0)->comment('0 => no website , 1 => has website');
            $table->unsignedTinyInteger('has_app')->default(0)->comment('0 => no app , 1 => has app');
            $table->boolean('contain_live')->default(0)->comment('0 => no live , 1 => has live');
            $table->boolean('is_certificate')->default(0)->comment('0 => no certificate , 1 => has certificate');
            $table->unsignedTinyInteger('course_type')->default(0)->comment('1=>online , 2 => offline , 3=> both');
            $table->unsignedTinyInteger('education_type')->default(0)->comment('1=>online , 2 => offline , 3=> both');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('image')->nullable();
            $table->foreign('parent_id')->references('id')->on('courses')->onDelete('set null');
            $table->timestamps();
        });
        Schema::create('course_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text("description")->nullable();
            $table->text("card_description")->nullable();
            $table->unique(['course_id', 'locale']);
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
