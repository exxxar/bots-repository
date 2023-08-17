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
        Schema::disableForeignKeyConstraints();
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->json('rules_if')->nullable();


            $table->foreignId('rules_else_page_id')
                ->comment("Страница в случае если")
                ->nullable()
                ->constrained('bot_pages');

            $table->string('rules_if_message',255)->nullable();
            $table->string('rules_else_message',255)->nullable();

        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->dropColumn('rules_if');
            $table->dropColumn('rules_else_page_id');
            $table->dropColumn('rules_if_message');
            $table->dropColumn('rules_else_message');
        });
        Schema::enableForeignKeyConstraints();

    }
};
