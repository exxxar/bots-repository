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
            $table->boolean('is_template')->default(false)
                ->comment("Является шаблоном");

            $table->string('template_description')->nullable()
                ->comment("Краткое описание шаблона");
        });

        Schema::table('bot_users', function (Blueprint $table) {
            $table->boolean('in_dialog_mode')->default(false)
                ->comment("Пользователь в режиме диалога");
        });

        Schema::table('bot_menu_slugs', function (Blueprint $table) {
            $table->unsignedInteger('bot_dialog_command_id')->nullable()
                ->comment("запускает цепочку диалогов");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('is_template');

            $table->dropColumn('template_description');
        });

        Schema::table('bot_users', function (Blueprint $table) {
            $table->dropColumn('in_dialog_mode');
        });

        Schema::table('bot_menu_slugs', function (Blueprint $table) {
            $table->dropColumn('bot_dialog_command_id');
        });
    }
};
