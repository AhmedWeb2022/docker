<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();
            $table->string('main_image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('target')->nullable();
            $table->bigInteger('number_of_corse')->nullable();
            $table->boolean('has_level')->default(false);
            $table->boolean('has_track')->default(false);
            // $table->bigInteger('created_by')->nullable();
            // $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('diploma_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_id')->constrained('diplomas')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('locale')->nullable();
            $table->unique(['diploma_id','locale']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diploma');
    }
};
