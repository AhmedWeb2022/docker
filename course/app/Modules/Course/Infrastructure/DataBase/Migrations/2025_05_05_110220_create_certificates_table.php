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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('certificate_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->longText('about')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('target_audience')->nullable();
            $table->longText('benefits')->nullable();
            $table->unique(['certificate_id', 'locale']);
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_translations');
    }
};
