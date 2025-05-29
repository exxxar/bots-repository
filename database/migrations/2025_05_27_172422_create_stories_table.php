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

        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->string('title', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->longText('description')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
