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
            //

            $table->longText("address")->nullable();
            $table->double("receiver_latitude")->default(0);
            $table->double("receiver_longitude")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("address");
            $table->dropColumn("receiver_latitude");
            $table->dropColumn("receiver_longitude");
        });
    }
};
