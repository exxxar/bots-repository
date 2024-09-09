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
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->json("config")->nullable();
        });

        Schema::table('bot_dialog_answers', function (Blueprint $table) {
            $table->boolean("need_print")->default(true);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn("config");
        });

        Schema::table('bot_dialog_answers', function (Blueprint $table) {
            $table->dropColumn("need_print");
        });

    }
};
