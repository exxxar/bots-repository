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

        Schema::create('bot_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('parent_id')->nullable()->constrained('bot_users');
            $table->boolean('is_vip')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_work')->default(false);
            $table->boolean('is_deliveryman')->default(false);
            $table->boolean('user_in_location')->default(false);
            $table->string('location_comment')->nullable();
            $table->double('current_latitude')->nullable();
            $table->double('current_longitude')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('age')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->boolean('sex')->default(true);
            $table->string('fio_from_telegram', 255)->nullable();
            $table->string('telegram_chat_id', 255)->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_users');
    }
};
