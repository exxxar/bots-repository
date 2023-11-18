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

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('file_id', 255)->nullable()->comment('идентификатор файла в TG');
            $table->integer('type')->default(0)->comment('Тип документа');
            $table->json('params')->nullable()->comment('выписанные параметры из документа');
            $table->foreignId('bot_id')->constrained();
            $table->foreignId('bot_user_id')->constrained();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
