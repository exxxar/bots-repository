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

        Schema::create('iikos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained();
            $table->string('api_login', 255)->nullable();
            $table->string('organization_id', 255)->nullable();
            $table->string('terminal_group_id', 255)->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iikos');
    }
};
