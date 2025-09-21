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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_standalone')->default(0)->comment("0 => no | 1 => yes");
            $table->foreign('parent_id')->references('id')->on('levels')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('level_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('level_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['level_id', 'locale']);
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
        Schema::dropIfExists('level_translations');
    }
};
