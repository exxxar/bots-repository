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

        Schema::create('bitrixes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained();
            $table->string('host', 255)->nullable();
            $table->string('client_id', 255)->nullable();
            $table->string('client_secret', 255)->nullable();
            $table->json('scopes')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitrixes');
    }
};
