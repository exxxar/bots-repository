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

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('article', 255)->nullable();
            $table->string('vk_product_id', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->longText('description')->nullable();
            $table->json('images')->nullable();
            $table->integer('type')->default(0);
            $table->double('old_price')->default('0');
            $table->double('current_price')->default('0');
            $table->json('variants')->nullable();
            $table->timestamp('in_stop_list_at')->nullable();
            $table->foreignId('bot_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
