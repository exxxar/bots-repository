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
            $table->foreignId('reply_keyboard_id')->nullable()->constrained('bot_menu_templates');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_dialog_commands', function (Blueprint $table) {
            $table->dropColumn('reply_keyboard_id');
        });
    }
};
