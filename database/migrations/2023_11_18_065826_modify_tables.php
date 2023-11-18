<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bot_id');
            $table->dropColumn('user_id');
            $table->dropColumn('receiver_get_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('bot_id')
                ->comment('из какого бота заказ')
                ->constrained('bots');

            $table->foreignId('deliveryman_id')
                ->constrained('bot_users');

            $table->foreignId('customer_id')
                ->nullable()
                ->comment("пользователь, который сделал заказ")
                ->constrained('bot_users');
        });

        Schema::enableForeignKeyConstraints();

        Schema::table('bot_users', function (Blueprint $table) {
            $table->boolean('is_manager')->default(false)
                ->change()->after("is_work");
            $table->boolean('is_deliveryman')->default(false)
                ->after("is_manager");
            $table->dropColumn('temporary');
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
