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

        Schema::create('y_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->string('login', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('company')->nullable();
            $table->json('fields')->nullable();
            $table->string('partner_token', 255)->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('y_clients');
    }
};
