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

        Schema::create('bot_products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('slug', 190)->unique();
            $table->longText('description')->nullable();
            $table->json('images')->nullable();
            $table->double('base_price')->default('0');
            $table->double('discount_price')->default('0');
            $table->double('weight')->default('0');
            $table->string('count')->default('0');
            $table->boolean('in_stock')->default(true);
            $table->json('specifications')->nullable();
            $table->json('variants')->nullable();
            $table->foreignId('owner_id')->constrained('users');
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
        Schema::dropIfExists('bot_products');
    }
};
