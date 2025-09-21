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
        Schema::table('diploma_level_tracks', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->default(0)->after('id');
            $table->boolean('is_active')->default(true)->after('order');
        }); 
        Schema::table('diploma_levels', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->default(0)->after('id');
            $table->boolean('is_active')->default(true)->after('order');
        }); 
        Schema::table('diplomas', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->default(0)->after('id');
            $table->boolean('is_active')->default(true)->after('order');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_level_tracks', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('is_active');
        });
        Schema::table('diploma_levels', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('is_active');
        });
        Schema::table('diplomas', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('is_active');
        });
    }
};
