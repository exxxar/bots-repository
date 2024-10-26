<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bot_pages', function (Blueprint $table) {
            //
            $table->string("price")->after("password_description")->nullable();
            $table->longText("price_description")->after("price")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->dropColumn("price");
            $table->dropColumn("price_description");

        });
    }
};
