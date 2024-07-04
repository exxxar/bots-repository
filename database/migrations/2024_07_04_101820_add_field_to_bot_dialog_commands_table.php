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
            $table->longText("custom_stored_value")->nullable();
        });

        Schema::table('bot_dialog_answers', function (Blueprint $table) {
            $table->longText("custom_stored_value")->nullable();
        });

        //BotDialogAnswer
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_dialog_commands', function (Blueprint $table) {
            $table->dropColumn("custom_stored_value");
        });

        Schema::table('bot_dialog_answers', function (Blueprint $table) {
            $table->dropColumn("custom_stored_value");
        });
    }
};
