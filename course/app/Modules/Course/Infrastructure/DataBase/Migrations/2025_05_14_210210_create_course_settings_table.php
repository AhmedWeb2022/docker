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
        Schema::create('course_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreignId("course_id")->nullable()
                ->constrained('courses')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->tinyInteger("code_status")->nullable()->comment("1 => move | 2 => static | 3 => show_hide");
            $table->boolean("is_security")->default(0);
            $table->boolean("is_watermark")->default(0);
            $table->boolean("is_voice")->default(0);
            $table->boolean("is_emulator")->default(0);
            $table->string("time_number")->nullable();
            $table->integer("number_of_voice")->nullable();
            $table->tinyInteger("watch_video")->nullable()->comment("محدد 1 | غير محدد 2");
            $table->integer("number_watch_video")->nullable();

            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_settings');
    }
};
