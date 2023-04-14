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

        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('bot_domain', 190)->unique();
            $table->string('bot_token', 255)->nullable();
            $table->string('bot_token_dev', 255)->nullable();
            $table->string('order_channel', 255)->nullable();
            $table->string('main_channel', 255)->nullable();
            $table->double('balance')->default('0');
            $table->double('tax_per_day')->default('0');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('info_link', 255)->nullable();
            $table->json('social_links')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('maintenance_message')->nullable();
            $table->foreignId('bot_type_id')->constrained();
            $table->double('level_1')->nullable();
            $table->double('level_2')->nullable();
            $table->double('level_3')->nullable();
            $table->longText('blocked_message')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};
