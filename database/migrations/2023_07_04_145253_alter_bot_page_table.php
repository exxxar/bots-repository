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
        Schema::disableForeignKeyConstraints();
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->foreignId('next_bot_dialog_command_id')
                ->nullable()
                ->constrained('bot_dialog_commands');

            $table->foreignId('next_bot_menu_slug_id')
                ->nullable()
                ->constrained('bot_menu_slugs');

        });

        Schema::table('bot_menu_slugs', function (Blueprint $table) {
            $table->dropColumn('bot_dialog_command_id');
        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->dropColumn('next_bot_dialog_command_id');
            $table->dropColumn('next_bot_menu_slug_id');
        });

        Schema::table('bot_menu_slugs', function (Blueprint $table) {
            $table->unsignedInteger('bot_dialog_command_id')->nullable()
                ->comment("запускает цепочку диалогов");
        });
    }
};
