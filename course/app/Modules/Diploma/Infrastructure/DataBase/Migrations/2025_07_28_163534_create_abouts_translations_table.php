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
        Schema::table('diploma_abouts', function (Blueprint $table) {
            $table->string('about')->nullable()->change();
            $table->boolean('is_active')->default(true);
        });
        Schema::create('diploma_about_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_about_id')
                ->constrained('diploma_abouts')
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unique(['diploma_about_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diploma_about_translations');
    }
};
