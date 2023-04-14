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

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->json('images')->nullable();
            $table->double('lat')->default('0');
            $table->double('lon')->default('0');
            $table->string('address', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('location_channel', 255)->nullable();
            $table->foreignId('company_id')->constrained();
            $table->boolean('is_active')->default(false);
            $table->boolean('can_booking')->default(false);
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
        Schema::dropIfExists('locations');
    }
};
