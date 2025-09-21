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
        Schema::create('diploma_abouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_id')
                ->constrained('diplomas')
                ->onDelete('cascade');
            $table->string('about');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diploma_abouts');
    }
};
