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

        Schema::create('manager_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_user_id')->nullable()->constrained();
            $table->longText('info')->nullable();
            $table->string('referral', 255)->nullable();
            $table->json('strengths')->nullable();
            $table->json('weaknesses')->nullable();
            $table->json('educations')->nullable();
            $table->json('social_links')->nullable();
            $table->json('skills')->nullable();
            $table->double('stable_personal_discount')->default('0');
            $table->double('permanent_personal_discount')->default('0');
            $table->integer('max_company_slot_count')->default(0);
            $table->integer('max_bot_slot_count')->default(0);
            $table->integer('balance')->default(0)->comment('Баланс счета менеджера');
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
        Schema::dropIfExists('manager_profiles');
    }
};
