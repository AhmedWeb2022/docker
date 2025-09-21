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
        Schema::create('referances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('referancable_id')->nullable();
            $table->string('referancable_type')->nullable();
            $table->timestamps();
        });

        Schema::create('referance_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referance_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['referance_id', 'locale']);
            $table->foreign('referance_id')->references('id')->on('referances')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referances');
        Schema::dropIfExists('referance_translations');
    }
};
