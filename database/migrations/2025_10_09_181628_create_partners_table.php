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

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->foreignId('bot_partner_id')->constrained('bots');
            $table->string('title', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('extra_charge')->default(0);
            $table->json('config')->nullable();
            $table->json('legal_info')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
