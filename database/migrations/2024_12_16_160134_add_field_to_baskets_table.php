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
        Schema::table('baskets', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()
                ->change()
                ->constrained("products");
            $table->foreignId('product_collection_id')->nullable()
                ->constrained("product_collections");
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('baskets', function (Blueprint $table) {
            //
        });
    }
};
