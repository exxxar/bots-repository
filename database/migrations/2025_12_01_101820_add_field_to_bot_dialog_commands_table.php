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
        Schema::table('bot_dialog_commands', function (Blueprint $table) {
            $table->json("videos")->nullable()->after("images");
            $table->json("documents")->nullable()->after("videos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_dialog_commands', function (Blueprint $table) {
            //
        });
    }
};
