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
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->dropColumn('video');
            $table->json("videos")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_pages', function (Blueprint $table) {
            $table->string('video',255)->nullable();
            $table->dropColumn('videos');
        });
    }
};
