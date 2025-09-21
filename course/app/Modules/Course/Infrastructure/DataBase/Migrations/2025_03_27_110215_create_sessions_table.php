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
        Schema::create('content_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedTinyInteger('is_file')->default(0)->comment('1=>file , 2 => link');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('can_skip')->default(0);
            $table->unsignedInteger('skip_rate')->default(0);
            $table->string('session_type')->default(0);
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->foreign('parent_id')->references('id')->on('content_sessions')->onDelete('set null');
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('content_session_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['session_id', 'locale']);
            $table->foreign('session_id')->references('id')->on('content_sessions')->onDelete('cascade');
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
