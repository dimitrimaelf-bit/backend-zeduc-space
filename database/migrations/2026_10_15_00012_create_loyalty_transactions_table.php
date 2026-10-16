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
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("order_id")->nullable()->constrained("orders");
            $table->enum("type", ['earn', 'redeem', 'referral', 'adjust', 'expire']);
            $table->integer("points");
            $table->string("description", 255);
            $table->timestamp("expires_at")->nullable();
            $table->timestamp("created_at")->useCurrent();
            $table->index(["user_id", "created_at"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
