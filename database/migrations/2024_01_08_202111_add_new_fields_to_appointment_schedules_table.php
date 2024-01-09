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
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->integer("year")->nullable();
            $table->integer("month")->nullable();
            $table->integer("week")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->dropColumn("year");
            $table->dropColumn("month");
            $table->dropColumn("week");
        });
    }
};
