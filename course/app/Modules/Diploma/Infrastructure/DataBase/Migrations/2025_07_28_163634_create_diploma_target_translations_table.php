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
        Schema::table('diploma_targets', function (Blueprint $table) {
            $table->string('target')->nullable()->change();
            $table->boolean('is_active')->default(true);
        });
        Schema::create('diploma_target_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_target_id')
                ->constrained('diploma_targets')
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unique(['diploma_target_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diploma_target_translations');
    }
};
