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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('id_number')->unique()->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_image')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->unsignedTinyInteger('is_email_verified')->default(0)->nullable();
            $table->unsignedTinyInteger('is_phone_verified')->default(0)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('stage_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->integer('wallet')->default(0)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('last_os')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
