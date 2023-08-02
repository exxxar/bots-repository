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
        Schema::table('companies', function (Blueprint $table) {
            $table->integer("vat_code")->nullable();
        });

        Schema::table('bots', function (Blueprint $table) {
            $table->boolean("auto_cashback_on_payments")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn("vat_code");
        });

        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn("auto_cashback_on_payments");
        });
    }
};
