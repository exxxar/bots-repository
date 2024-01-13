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

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->longText('text')->nullable();
            $table->string('media_content')->nullable();
            $table->string('content_type')->nullable();
            $table->boolean('is_multiply')->default(false);
            $table->boolean('is_open')->default(false);
            $table->integer('round')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
