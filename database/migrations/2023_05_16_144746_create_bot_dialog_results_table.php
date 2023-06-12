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

        Schema::create('bot_dialog_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_user_id')->nullable()->constrained();
            $table->foreignId('bot_dialog_command_id')->nullable()->constrained('bot_dialog_commands');
            $table->json('current_input_data')->nullable();
            $table->json('summary_input_data')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_dialog_results');
    }
};
