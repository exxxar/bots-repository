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

        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained("products");
            $table->integer('count')->default(1);
            $table->foreignId('bot_user_id')->constrained("bot_users");
            $table->foreignId('bot_id')->constrained("bots");
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baskets');
    }
};
