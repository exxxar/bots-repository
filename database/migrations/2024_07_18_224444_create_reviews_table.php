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

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained("bots");
            $table->foreignId('order_id')->nullable()->constrained("orders");
            $table->foreignId('bot_user_id')->nullable()->constrained("bot_users");
            $table->foreignId('product_id')->nullable()->constrained("products");
            $table->string('text', 255)->nullable()->comment('текст отзыва');
            $table->json('images')->nullable();
            $table->double('rating')->default(5);
            $table->timestamp('send_review_at')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->double('rating')->after('title')->default(5);
            $table->foreignId('sub_shop_id')->after('bot_id')->nullable()->constrained();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
