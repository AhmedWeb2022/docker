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
        Schema::create('website_section_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_section_id')->constrained()->cascadeOnDelete();
            $table->string('file')->nullable();
            $table->string('link')->nullable();
            $table->string('alt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_section_attachments');
    }
};
