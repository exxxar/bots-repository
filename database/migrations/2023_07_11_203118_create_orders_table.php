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

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained('companies');
            $table->foreignId('user_id')->constrained();
            $table->json('delivery_service_info')->nullable()->comment('Информация о сервисе доставки');
            $table->json('deliveryman_info')->nullable()->comment('Берется из внешнего доверенного сервиса');
            $table->json('product_details')->nullable()->comment('Дамп заказанных продуктов');
            $table->integer('product_count')->default(0);
            $table->double('summary_price')->default('0');
            $table->double('delivery_price')->default('0');
            $table->double('delivery_range')->default('0');
            $table->double('deliveryman_latitude')->default('0');
            $table->double('deliveryman_longitude')->default('0');
            $table->longText('delivery_note')->nullable();
            $table->string('receiver_name', 255)->nullable();
            $table->string('receiver_phone', 255)->nullable();
            $table->foreignId('receiver_get_id')->constrained('geos');
            $table->integer('status')->default(0);
            $table->integer('order_type')->default(0);
            $table->timestamp('payed_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
