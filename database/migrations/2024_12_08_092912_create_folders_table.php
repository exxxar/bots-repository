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

        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->nullable()->constrained('bots');
            $table->string('title', 255)->nullable();
            $table->tinyInteger('type')->default(\App\Enums\FolderTypeEnum::Page->value);
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
