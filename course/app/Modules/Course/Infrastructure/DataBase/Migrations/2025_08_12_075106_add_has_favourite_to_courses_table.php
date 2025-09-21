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
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('has_favourite')->default(0)->comment('0 => no favourite , 1 => has_favourite');
            $table->boolean('has_hidden')->default(0)->comment('0 => no hidden , 1 => has_hidden');
            $table->boolean("has_discount")->default(0)->comment('0 => no discount , 1 => has_discount');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('has_favourite');
            $table->dropColumn('has_hidden');
            $table->dropColumn('has_discount');
        });
    }
};
