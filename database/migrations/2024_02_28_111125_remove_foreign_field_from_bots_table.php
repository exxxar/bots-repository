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
        Schema::table('bots', function (Blueprint $table) {

            $table->dropForeign(['creator_id']);

          /*  $table->foreignId('creator_id')
                ->change()
                ->nullable();*/

            //$table->dropForeign('creator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            //
        });
    }
};
