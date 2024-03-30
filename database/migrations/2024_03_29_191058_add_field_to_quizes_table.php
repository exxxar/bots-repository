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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('success_inline_keyboard_id')->nullable()->constrained("bot_menu_templates");
            $table->foreignId('failure_inline_keyboard_id')->nullable()->constrained("bot_menu_templates");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('success_inline_keyboard_id');
            $table->dropColumn('failure_inline_keyboard_id');
        });
    }
};
