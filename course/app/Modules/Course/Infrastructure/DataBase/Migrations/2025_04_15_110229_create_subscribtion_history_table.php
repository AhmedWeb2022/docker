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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->string('receipt')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0:pending, 1:accepted, 2:rejected , 3:cancelled');
            $table->unsignedTinyInteger('paid_status')->default(0)->comment('0:unpaid, 1:paid');
            $table->double('price')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribtions');
    }
};
