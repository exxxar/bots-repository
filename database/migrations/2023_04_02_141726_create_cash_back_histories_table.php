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

        Schema::create('cash_back_histories', function (Blueprint $table) {
            $table->id();
            $table->double('money_in_check')->default('0');
            $table->string('description', 255)->nullable();
            $table->integer('operation_type')->default(0);
            $table->double('amount')->default(0);
            $table->integer('level')->default(1);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('bot_id')->constrained();
            $table->foreignId('employee_id')->constrained('users');
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
        Schema::dropIfExists('cash_back_histories');
    }
};
