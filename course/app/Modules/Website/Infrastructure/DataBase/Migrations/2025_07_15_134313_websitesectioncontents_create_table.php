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
        Schema::create('website_section_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_section_id')->constrained()->cascadeOnDelete();
            $table->morphs('contentable');// contentable_type, contentable_id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_section_contents');
    }
};
