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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean("polling_mode")->default(false)->after("completed_at");
            $table->boolean("round_mode")->default(false)->after("completed_at");
            $table->integer("try_count")->default(1)->after("completed_at");
            $table->boolean("is_active")->default(true)->after("completed_at");
            $table->double("success_percent")->default(50)->after("completed_at");
            $table->json("success_message")->nullable()->after("completed_at");
            $table->json("failure_message")->nullable()->after("completed_at");
        });

        Schema::table("quiz_questions", function (Blueprint $table){
            $table->longText("success_message")->nullable()->after("round");
            $table->longText("success_media_content")->nullable()->after("round");
            $table->longText("failure_message")->nullable()->after("round");
            $table->longText("failure_media_content")->nullable()->after("round");
            $table->string("success_media_content_type")->nullable()->after("round");
            $table->string("failure_media_content_type")->nullable()->after("round");

        });

        Schema::table('quiz_commands', function (Blueprint $table) {
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('captain_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn("polling_mode");
            $table->dropColumn("round_mode");
            $table->dropColumn("is_active");
            $table->dropColumn("success_percent");
            $table->dropColumn("success_message");
            $table->dropColumn("failure_message");
        });

        Schema::table("quiz_questions", function (Blueprint $table){
            $table->dropColumn("success_message");
            $table->dropColumn("failure_message");
            $table->dropColumn("success_media_content");
            $table->dropColumn("failure_media_content");
            $table->dropColumn("success_media_content_type");
            $table->dropColumn("failure_media_content_type");
        });

        Schema::table('quiz_commands', function (Blueprint $table) {
            $table->dropColumn('creator_id');
            $table->dropColumn('captain_id');
        });
    }
};
