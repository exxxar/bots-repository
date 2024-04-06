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

        Schema::create('bot_dialog_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_dialog_command_id')->nullable()->constrained('bot_dialog_commands');
            $table->string('answer', 255)->nullable();
            $table->string('pattern', 255)->nullable();
            $table->foreignId('next_bot_dialog_command_id')->nullable()->constrained('bot_dialog_commands');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_dialog_answers');
    }
};
