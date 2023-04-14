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

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('slug', 190)->unique();
            $table->longText('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->json('phones')->nullable();
            $table->json('links')->nullable();
            $table->string('email', 255)->nullable();
            $table->json('schedule')->nullable();
            $table->string('manager', 255)->nullable();
            $table->boolean('is_active')->default(false);
            $table->longText('blocked_message')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->unsignedInteger('owner_id')->nullable();
            $table->timestamp('blocked_at')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
