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

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('bot_id')->constrained();
            $table->string('payload', 128)->unique();
            $table->string('currency', 5)->default('RUB');
            $table->integer('total_amount')->default(0);
            $table->integer('status')->default(0);
            $table->json('order_info')->nullable();
            $table->json('products_info')->nullable();
            $table->json('shipping_address')->nullable();
            $table->string('telegram_payment_charge_id', 255)->nullable();
            $table->string('provider_payment_charge_id', 255)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
