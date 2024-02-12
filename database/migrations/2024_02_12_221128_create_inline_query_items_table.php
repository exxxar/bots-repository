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

        Schema::create('inline_query_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inline_query_slug_id')->constrained();
            $table->integer('type')->default(0);
            $table->string('title', 255)->nullable();
            $table->longText('description')->nullable();
            $table->json('input_message_content')->nullable();
            $table->foreignId('inline_keyboard_id')->nullable()
                ->constrained('bot_menu_templates');
            $table->json('custom_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inline_query_items');
    }
};
