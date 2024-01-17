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
            $table->integer("try_count")->default(1)->after("polling_mode");
            $table->boolean("is_active")->default(true)->after("try_count");
            $table->double("success_percent")->default(50)->after("is_active");
            $table->json("success_message")->nullable()->after("success_percent");
            $table->json("failure_message")->nullable()->after("failure_message");
        });

        Schema::table("quiz_questions", function (Blueprint $table){
            $table->longText("success_message")->nullable()->after("round");
            $table->longText("failure_message")->nullable()->after("success_message");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn("polling_mode");
            $table->dropColumn("is_active");
            $table->dropColumn("success_percent");
            $table->dropColumn("success_message");
            $table->dropColumn("failure_message");
        });

        Schema::table("quiz_questions", function (Blueprint $table){
            $table->dropColumn("success_message");
            $table->dropColumn("failure_message");
        });
    }
};
