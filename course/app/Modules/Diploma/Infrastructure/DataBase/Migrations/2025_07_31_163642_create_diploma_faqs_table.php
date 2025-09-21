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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreignId('diploma_id')->nullable()->constrained('diplomas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->text('question')->nullable();
            $table->text("answer")->nullable();
            $table->string('locale')->nullable();
            $table->unsignedBigInteger('faq_id')->nullable();
            $table->foreign('faq_id')
                ->references('id')
                ->on('faqs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unique(['faq_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_translations');
        Schema::dropIfExists('faqs');
    }
};
