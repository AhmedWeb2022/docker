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
        Schema::table('course_settings', function (Blueprint $table) {
            $table->float('price')->default(0)->after('number_watch_video');
            $table->unsignedTinyInteger('has_discount')->default(0)->after('price');
            $table->unsignedTinyInteger('discount_type')->default(0)->after('has_discount')->comment('1 => fixed, 2 => percentage');
            $table->float('discount')->default(0)->after('has_discount');
            $table->string('telegram_link')->nullable()->after('discount');
            $table->string('whatsapp_link')->nullable()->after('telegram_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_settings', function (Blueprint $table) {
            //
        });
    }
};
