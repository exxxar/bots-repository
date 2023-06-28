<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bot_menu_slugs', function (Blueprint $table) {
            $table->json('config')->nullable();
            $table->boolean('is_global')->default(false);
            $table->timestamp('deprecated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::table('bot_pages', function (Blueprint $table) {
            $table->foreignId('next_page_id')
                ->nullable()
                ->constrained('bot_pages');

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_menu_slugs', function (Blueprint $table) {
            $table->dropColumn('config');
            $table->dropColumn('is_global');
            $table->dropColumn('deprecated_at');
            $table->dropColumn('deleted_at');
        });

        Schema::table('bot_pages', function (Blueprint $table) {
            $table->dropColumn('next_page_id');
        });
    }
};
