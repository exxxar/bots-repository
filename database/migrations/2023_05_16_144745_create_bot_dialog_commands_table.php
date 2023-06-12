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

        Schema::create('bot_dialog_commands', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255);
            $table->longText('pre_text')->nullable();
            $table->longText('post_text')->nullable();
            $table->longText('error_text')->nullable();
            $table->foreignId('bot_id')->constrained();
            $table->string('input_pattern', 255)->nullable();
            $table->foreignId('inline_keyboard_id')->nullable()->constrained('bot_menu_templates');
            $table->json('images')->nullable();

            $table->foreignId('next_bot_dialog_command_id')
                ->nullable()
                ->constrained('bot_dialog_commands');

            $table->foreignId('bot_dialog_group_id')
                ->nullable()
                ->constrained('bot_dialog_groups');

            $table->string('result_channel', 255)->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_dialog_commands');
    }
};
