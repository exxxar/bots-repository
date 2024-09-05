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

        Schema::create('product_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained();
            $table->foreignId('owner_id')->nullable()->constrained('bot_users');
            $table->string('title', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(false);
            $table->integer('discount')->default(0)->comment('скидка на набор в %');
            $table->integer('order_position')->default(0);
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
        Schema::dropIfExists('product_collections');
    }
};
