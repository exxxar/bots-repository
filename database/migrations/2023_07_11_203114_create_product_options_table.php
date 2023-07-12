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

        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('value', 255)->nullable();
            $table->string('section', 255)->nullable();
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
