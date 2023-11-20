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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer("service_rating")
                ->default(0)
                ->nullable()
                ->after("delivery_note");

            $table->string("service_review", 255)
                ->nullable()
                ->after("service_rating");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("service_rating");
            $table->dropColumn("service_review");
        });
    }
};
