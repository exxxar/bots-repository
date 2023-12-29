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

        Schema::create('appointment_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_event_id')->nullable()->constrained();
            $table->string('title', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('category', 255)->nullable();
            $table->json('images')->nullable();
            $table->double('price')->default('0');
            $table->double('discount_price')->default('0');
            $table->boolean('need_prepayment')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_services');
    }
};
