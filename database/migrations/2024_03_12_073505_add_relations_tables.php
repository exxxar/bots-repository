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

        Schema::create('manager_profile_has_scripts', function (Blueprint $table) {
            $table->foreignId('manager_profile_id')->constrained();
            $table->foreignId('bot_menu_slug_id')->constrained();
        });

        Schema::create('promo_code_has_scripts', function (Blueprint $table) {
            $table->foreignId('promo_code_id')->constrained();
            $table->foreignId('bot_menu_slug_id')->constrained();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
