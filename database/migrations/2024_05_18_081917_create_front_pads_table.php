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

        Schema::create('front_pads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained();
            $table->string('hook_url', 255)->nullable()->comment('url для отправки вебхука по текущему заказу');
            $table->string('channel', 255)->nullable()->comment('канал продаж');
            $table->string('affiliate', 255)->nullable()->comment('филиал');
            $table->string('point', 255)->nullable()->comment('точка продаж');
            $table->string('token', 255)->nullable()->comment('тоукен интеграции');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_pads');
    }
};
