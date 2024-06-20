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


        Schema::table('bot_dialog_results', function (Blueprint $table) {
            $table->json("variables")->nullable();
        });

        Schema::table('bot_dialog_commands', function (Blueprint $table) {
            $table->string("use_result_as")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::table('bot_dialog_results', function (Blueprint $table) {
            $table->dropColumn("variables");
        });

        Schema::table('bot_dialog_commands', function (Blueprint $table) {
            $table->dropColumn("use_result_as");
        });
    }
};
