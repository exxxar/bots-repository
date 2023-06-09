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

        Schema::table('action_statuses', function (Blueprint $table) {
            $table->foreignId('slug_id')
                ->nullable()
                ->constrained("bot_menu_slugs");
            $table->dropColumn('script');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::disableForeignKeyConstraints();

        Schema::table('action_statuses', function (Blueprint $table) {
            $table->dropColumn('slug_id');
            $table->string('script', 255);
        });

        Schema::enableForeignKeyConstraints();
    }
};
