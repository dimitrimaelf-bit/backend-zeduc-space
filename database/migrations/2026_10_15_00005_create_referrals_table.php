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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId("referrer_user_id")->constrained("users");
            $table->foreignId("referred_user_id")->constrained("users");
            $table->foreignId("code_id")->constrained("referral_codes");
            $table->enum("status", ["pending", "rewarded", "cancelled"])->default("pending");
            $table->timestamp("rewarded_at")->nullable();
            $table->timestamp("created_at")->useCurrent();
            $table->index("referrer_user_id");
            $table->index("status");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
