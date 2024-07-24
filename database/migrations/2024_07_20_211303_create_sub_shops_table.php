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

        Schema::create('sub_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained();
            $table->string('title', 255)->nullable()->comment('название внутреннего магазина');
            $table->string('keyword', 255)->nullable()->comment('идентификатор-метка магазина');
            $table->string('image', 255)->nullable();
            $table->json('schedule')->nullable();
            $table->json('config')->nullable()->comment('дополнительная настройка магазина');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_shops');
    }
};
