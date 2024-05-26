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
        Schema::table('products', function (Blueprint $table) {
            $table->string("frontpad_article")->nullable()->after("vk_product_id");
            $table->string("iiko_article")->nullable()->after("frontpad_article");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn("frontpad_article")->nullable();
            $table->dropColumn("iiko_article")->nullable();
        });
    }
};
