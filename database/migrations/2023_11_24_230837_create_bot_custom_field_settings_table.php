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

        Schema::create('bot_custom_field_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->integer('type')->default(0)->nullable();
            $table->string('key')->nullable();
            $table->string('label', 255)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('required');
            $table->string('validate_pattern', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_custom_field_settings');
    }
};
