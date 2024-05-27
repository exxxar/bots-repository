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
        Schema::table('front_pads', function (Blueprint $table) {
            $table->json("statuses")->nullable();
            $table->json("pays")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('front_pads', function (Blueprint $table) {
            $table->dropColumn("statuses");
            $table->dropColumn("pays");
        });
    }
};
