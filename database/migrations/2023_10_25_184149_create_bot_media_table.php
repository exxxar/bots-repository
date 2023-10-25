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

        Schema::create('bot_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->foreignId('bot_user_id')->constrained();
            $table->string('file_id', 255)->nullable();
            $table->longText('caption')->nullable();
            $table->string('type', 255);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_media');
    }
};
