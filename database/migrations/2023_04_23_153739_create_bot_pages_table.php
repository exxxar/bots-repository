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

        Schema::create('bot_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_menu_slug_id')->constrained();
            $table->longText('content')->nullable();
            $table->json('images')->nullable();
            $table->foreignId('reply_keyboard_id')->nullable()->constrained('bot_menu_templates');
            $table->foreignId('inline_keyboard_id')->nullable()->constrained('bot_menu_templates');
            $table->foreignId('bot_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_pages');
    }
};
