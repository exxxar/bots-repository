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

        Schema::create('appointment_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_event_id')->nullable()->constrained();
            $table->foreignId('appointment_schedule_id')->nullable()->constrained();
            $table->foreignId('bot_user_id')->nullable()->constrained();
            $table->double('rating')->default(0);
            $table->longText('text')->nullable();
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
        Schema::dropIfExists('appointment_reviews');
    }
};
