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
        Schema::create('course_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("course_id")->constrained("courses")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('created_by')->nullable();
            $table->tinyInteger("discount_type")->default(0)->comment('0 => percentage , 1 => fixed');
            $table->float("discount_amount")->default(0);
            $table->date('discount_from_date')->nullable();
            $table->date('discount_to_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_offers');
    }
};
