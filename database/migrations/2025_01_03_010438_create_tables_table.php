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


        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained("bots");
            $table->foreignId('creator_id')->nullable()->constrained('bot_users');
            $table->foreignId('officiant_id')->nullable()->constrained('bot_users');
            $table->string('number', 255)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->json('additional_services')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
        });

        Schema::table('baskets', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable()->constrained("tables");
            $table->timestamp('table_approved_at')->nullable();
        });

        Schema::create('table_bot_user_clients', function (Blueprint $table) {
            $table->foreignId('bot_user_id');
            $table->foreignId('table_id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
