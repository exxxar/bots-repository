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
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->string("password")->after("content")->nullable();
            $table->longText("password_description")->after("password")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->dropColumn("password");
            $table->dropColumn("password_description");
        });
    }
};
