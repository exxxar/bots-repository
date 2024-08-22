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

        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained();
            $table->string('title', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('key', 255)->nullable()->comment('для распределения товаров');
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('shops');
    }
};
