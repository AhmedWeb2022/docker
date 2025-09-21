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
        Schema::create('live_answer_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('live_answer_id')->nullable();
            $table->string('media')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1 => image , 2 => video , 3 => audio');
            $table->string('alt')->nullable();
            $table->foreign('live_answer_id')->references('id')->on('live_answers')->onDelete('cascade');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_answer_attachments');
    }
};
