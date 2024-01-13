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

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->longText('description')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->integer('display_type')->default(0);
            $table->double('time_limit')->default('0');
            $table->boolean('show_answers')->default(false);
            $table->foreignId('bot_id')->constrained();
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
        Schema::dropIfExists('quizzes');
    }
};
