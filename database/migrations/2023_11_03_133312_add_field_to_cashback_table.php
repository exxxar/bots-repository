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
        Schema::table('cash_backs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('bot_user_id')->nullable()->constrained("bot_users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_backs', function (Blueprint $table) {
            //
        });
    }
};
