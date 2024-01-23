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

        Schema::create('chat_logs', function (Blueprint $table) {
            $table->id();
            $table->longText('text')->nullable();
            $table->string('media_content')->nullable();
            $table->string('content_type')->nullable();
            $table->foreignId('bot_id')->constrained();
            $table->foreignId('form_bot_user_id')->nullable()->constrained('bot_users');
            $table->foreignId('to_bot_user_id')->nullable()->constrained('bot_users');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
};
