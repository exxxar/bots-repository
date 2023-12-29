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

        Schema::create('appointment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained();
            $table->string('title', 255)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->longText('description')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_group')->default(false);
            $table->integer('max_people')->default(0)->nullable();
            $table->integer('min_people')->default(0)->nullable();
            $table->longText('on_start_appointment')->nullable();
            $table->longText('on_cancel_appointment')->nullable();
            $table->longText('on_after_appointment')->nullable();
            $table->longText('on_repeat_appointment')->nullable();
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
        Schema::dropIfExists('appointment_events');
    }
};
