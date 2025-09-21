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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedTinyInteger('type')->nullable();
            $table->string('number')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedTinyInteger('has_end_date')->default(0);
            $table->unsignedTinyInteger('status')->default(0)->comment('0:pending, 1:success, 2:failed , 3:cancelled');
            $table->unsignedTinyInteger('paid_status')->default(0)->comment('0:unpaid, 1:paid');
            $table->unsignedTinyInteger('has_receipt')->default(0)->comment('0:yes, 1:no');
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
