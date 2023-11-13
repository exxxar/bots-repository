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
        Schema::table('bots', function (Blueprint $table) {
            //
            $table->integer('cashback_fire_percent')
                ->default(0)
                ->comment("процент сгорания, не больше 100");
            $table->integer('cashback_fire_period')
                ->default(0)
                ->comment("период сгорания в днях");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('cashback_fire_percent');
            $table->dropColumn('cashback_fire_period');
        });
    }
};
